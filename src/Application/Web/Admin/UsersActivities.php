<?php

namespace Application\Web\Admin;

use Application\Repository\UserActivityRepository;
use Application\Web\Admin\QueryString\ListQS;
use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class UsersActivities extends BaseController
{
    /**
     * @var ListQS
     */
    protected $listQS;

    public function get()
    {
        $this->getAuthService()->checkPermission('admin.users.activities.list');

        $pager = $this->getPager();
        $paginationHtml = $this->getPaginationHtml($pager);

        $templateParams = array(
            'pager' => $pager,
            'pagination_html' => $paginationHtml,
            'qs' => $this->getListQS()
        );

        if ($this->getSession()->has('deleted_item')) {
            $deletedItem = $this->getSession()->get('deleted_item', 0);
            $this->getSession()->remove('deleted_item');
            $templateParams['message'] = $deletedItem . ' kullanıcı aktivitesi silindi.';
            $templateParams['message_type'] = 'success';
        }

        return $this->render('admin/users-activities/list.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.users.activities.delete');

        $ids = $this->getRequest()->get('id', array());
        $redirectUrl = '/admin/users-activities?' . $this->getListQS()->createQueryString();

        if (empty($ids)) {
            return $this->redirect($redirectUrl);
        }

        $affectedRows = $this->getUserActivityRepository()->deleteIds($ids);

        // Yapılan işlem kullanıcı aktivitesi olarak ekleniyor.
        $this->getAuthService()
            ->newUserActivity('admin.users.activities.delete', array('ids' => $ids, 'affectedRows' => $affectedRows));

        // Silinen kayıt sayısı gösterilmek üzere kayıt altına alınıyor.
        $this->getSession()->set('deleted_item', $affectedRows);
        return $this->redirect($redirectUrl);
    }

    /**
     * @param Pagerfanta $pager
     * @return mixed
     */
    protected function getPaginationHtml(Pagerfanta $pager)
    {
        $userController = $this;
        $routeGenerator = function ($page) use ($userController) {
            $qs = $userController->getListQS()->createQueryString(array('page')) . '&page=' . $page;
            return '/admin/users-activities?' . $qs;
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
        $query = $this->getUserActivityRepository()
            ->createQueryBuilder('ua')
            ->orderBy('ua.createdAt', Criteria::DESC);

        // Arama sonucuna göre filtrele
        if ($this->getListQS()->hasSearch()) {
            $likeStr = '%' . $this->getListQS()->getSearch() . '%';
            $query
                ->join('ua.user', 'u')
                ->where(
                    $query->expr()->orX(
                        $query->expr()->like('ua.activity', $query->expr()->literal($likeStr)),
                        $query->expr()->like('ua.data', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.username', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.name', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.surname', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.email', $query->expr()->literal($likeStr))
                    )
                );
        }

        $query = $query->getQuery();

        $adapter = new DoctrineORMAdapter($query);
        $pager = new Pagerfanta($adapter);

        try {
            $pager->setMaxPerPage(10)
                ->setCurrentPage($this->getListQS()->getPage());
        } catch (\Exception $exception) {

        }

        return $pager;
    }

    /**
     * @return ListQS
     */
    public function getListQS()
    {
        if ($this->listQS == null) {
            $this->listQS = new ListQS($this->getRequest());
        }

        return $this->listQS;
    }

    /**
     * @return UserActivityRepository
     */
    protected function getUserActivityRepository()
    {
        return  $this->getEntityManager()->getRepository('Application\Entity\UserActivity');
    }
}
