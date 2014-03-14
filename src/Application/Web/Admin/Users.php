<?php

namespace Application\Web\Admin;

use Application\Repository\RoleRepository;
use Application\Repository\UserRepository;
use Application\Web\Admin\QueryString\UsersQS;
use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class Users extends BaseController
{
    /**
     * @var UsersQS
     */
    protected $userQS;

    public function get()
    {
        $this->getAuthService()->checkPermission('admin.users.list');

        $pager = $this->getPager();
        $paginationHtml = $this->getPaginationHtml($pager);

        $templateParams = array(
            'pager' => $pager,
            'pagination_html' => $paginationHtml,
            'all_roles' => $this->getRoleRepository()->findAll(),
            'qs' => $this->getUsersQS()
        );

        if ($this->getSession()->has('deleted_item')) {
            $deletedItem = $this->getSession()->get('deleted_item', 0);
            $this->getSession()->remove('deleted_item');
            $templateParams['message'] = $deletedItem . ' kullanıcı silindi.';
            $templateParams['message_type'] = 'success';
        }

        return $this->render('admin/users/list.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.users.delete');

        $ids = $this->getRequest()->get('id', array());
        $redirectUrl = '/admin/users?' . $this->getUsersQS()->createQueryString();

        if (empty($ids)) {
            return $this->redirect($redirectUrl);
        }

        $affectedRows = $this->getUserRepository()->deleteIds($ids);

        // Yapılan işlem kullanıcı aktivitesi olarak ekleniyor.
        $this->getAuthService()
            ->newUserActivity('admin.users.delete', array('ids' => $ids, 'affectedRows' => $affectedRows));

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
            $qs = $userController->getUsersQS()->createQueryString(array('page')) . '&page=' . $page;
            return '/admin/users?' . $qs;
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
        $query = $this->getEntityManager()->getRepository('Application\Entity\User')
            ->createQueryBuilder('u')
            ->orderBy('u.username', Criteria::ASC);

        // Arama sonucuna göre filtrele
        if ($this->getUsersQS()->hasSearch()) {
            $likeStr = '%' . $this->getUsersQS()->getSearch() . '%';
            $query
                ->where(
                    $query->expr()->orX(
                        $query->expr()->like('u.username', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.name', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.surname', $query->expr()->literal($likeStr)),
                        $query->expr()->like('u.email', $query->expr()->literal($likeStr))
                    )
                );
        }

        // Role göre filtrele
        if ($this->getUsersQS()->hasRoleId()) {
            $query
                ->join('u.role', 'r')
                ->where('r.id = :role_id')
                ->setParameter('role_id', $this->getUsersQS()->getRoleId());
        }

        $query = $query->getQuery();

        $adapter = new DoctrineORMAdapter($query);
        $pager = new Pagerfanta($adapter);

        try {
            $pager->setMaxPerPage(10)
                ->setCurrentPage($this->getUsersQS()->getPage());
        } catch (\Exception $exception) {

        }

        return $pager;
    }

    /**
     * @return UsersQS
     */
    public function getUsersQS()
    {
        if ($this->userQS == null) {
            $this->userQS = new UsersQS($this->getRequest());
        }

        return $this->userQS;
    }

    /**
     * @return RoleRepository
     */
    protected function getRoleRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Role');
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return  $this->getEntityManager()->getRepository('Application\Entity\User');
    }
}
