<?php

namespace Application\Web\Admin;

use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class Roles extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.roles.list');

        $pager = $this->getPager();
        $paginationHtml = $this->getPaginationHtml($pager);

        $templateParams = array(
            'pager' => $pager,
            'pagination_html' => $paginationHtml
        );

        if ($this->getSession()->has('deleted_item')) {
            $deletedItem = $this->getSession()->get('deleted_item', 0);
            $this->getSession()->remove('deleted_item');
            $templateParams['message'] = $deletedItem . ' rol silindi.';
            $templateParams['message_type'] = 'success';
        }

        return $this->render('admin/roles/list.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.roles.delete');

        // Parametreleri al ve kayıtları sil.
        $ids = $this->getRequest()->get('id', array());
        $page = $this->getRequest()->get('page', 1);

        if (!is_array($ids) || empty($ids)) {
            return $this->redirect('/admin/roles?page=' . $page);
        }

        $qb = $this->getEntityManager()->getRepository('Application\Entity\Role')->createQueryBuilder('r');
        $affectedRows = $qb->where($qb->expr()->in('r.id', $ids))->delete()->getQuery()->execute();

        // Yapılan işlem kullanıcı aktivitesi olarak ekleniyor.
        $this->getAuthService()
            ->newUserActivity('admin.roles.delete', array('ids' => $ids, 'affectedRows' => $affectedRows));

        // Silinen kayıt sayısı gösterilmek üzere kayıt altına alınıyor.
        $this->getSession()->set('deleted_item', $affectedRows);
        return $this->redirect('/admin/roles?page=' . $page);
    }

    /**
     * @param Pagerfanta $pager
     * @return mixed
     */
    protected function getPaginationHtml(Pagerfanta $pager)
    {
        $routeGenerator = function ($page) {
            return '/admin/roles?page=' . $page;
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
        $query = $this->getEntityManager()->getRepository('Application\Entity\Role')
            ->createQueryBuilder('r')
            ->orderBy('r.name', Criteria::ASC)
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
