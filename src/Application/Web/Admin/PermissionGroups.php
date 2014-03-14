<?php

namespace Application\Web\Admin;

use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class PermissionGroups extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.permissions.group.list');

        $pager = $this->getPager();
        $paginationHtml = $this->getPaginationHtml($pager);

        $templateParams = array(
            'pager' => $pager,
            'pagination_html' => $paginationHtml
        );

        if ($this->getSession()->has('deleted_item')) {
            $deletedItem = $this->getSession()->get('deleted_item', 0);
            $this->getSession()->remove('deleted_item');
            $templateParams['message'] = $deletedItem . ' izin silindi.';
            $templateParams['message_type'] = 'success';
        }

        return $this->render('admin/permission-groups/list.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.permissions.group.delete');

        $ids = $this->getRequest()->get('id', array());
        $page = $this->getRequest()->get('page', 1);

        $qb = $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup')->createQueryBuilder('pg');
        $affectedRows = $qb->where($qb->expr()->in('pg.id', $ids))->delete()->getQuery()->execute();

        $this->getSession()->set('deleted_item', $affectedRows);
        return $this->redirect('/admin/permission-groups?page=' . $page);
    }

    /**
     * @param Pagerfanta $pager
     * @return mixed
     */
    protected function getPaginationHtml(Pagerfanta $pager)
    {
        $routeGenerator = function ($page) {
            return '/admin/permission-groups?page=' . $page;
        };
        $pagerView = new TwitterBootstrap3View();
        $options = array(
            'prev_message' => 'Önceki',
            'next_message' => 'Sonraki'
        );
        $paginationHtml = $pagerView->render($pager, $routeGenerator, $options);
        return $paginationHtml;
    }

    /**
     * @return Pagerfanta
     */
    protected function getPager()
    {
        $query = $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup')
            ->createQueryBuilder('pg')
            ->orderBy('pg.name', Criteria::ASC)
            ->getQuery();

        $page = intval($this->getRequest()->get('page', 1));
        $adapter = new DoctrineORMAdapter($query);
        $pager = new Pagerfanta($adapter);

        try {
            $pager->setMaxPerPage(10)
                ->setCurrentPage($page);
        } catch (\Exception $exception) {

        }

        return $pager;
    }
}
