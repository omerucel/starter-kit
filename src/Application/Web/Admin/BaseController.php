<?php

namespace Application\Web\Admin;

use Application\Entity\User;
use Application\Entity\UserActivity;
use Application\Entity\UserRepository;
use Application\Web\Admin;
use MiniFrame\WebApplication\Controller;

abstract class BaseController extends Controller
{
    /**
     * @var User
     */
    protected $currentUser;

    public function preDispatch()
    {
        if ($this->getCurrentUser() == null && !$this instanceof Login) {
            return $this->redirect('/admin/login');
        }

        if ($this->getCurrentUser() != null && $this instanceof Login) {
            return $this->redirect('/admin');
        }

        return parent::preDispatch();
    }

    /**
     * @return Admin
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        $adminUserId = $this->getSession()->get('admin_user_id', 0);
        if ($adminUserId > 0) {
            $this->currentUser = $this->getEntityManager()
                ->getRepository('Application\Entity\User')
                ->find($adminUserId);
        }
        return $this->currentUser;
    }

    /**
     * @param $key
     * @throws Exception\AccessDenied
     */
    public function checkPermission($key)
    {
        if (!$this->hasPermission($key)) {
            throw new Admin\Exception\AccessDenied('User does not have ' . $key . ' permission.');
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasPermission($key)
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->getEntityManager()->getRepository('Application\Entity\User');
        return $userRepository->hasPermission($key);
    }

    /**
     * @param $activity
     * @param array $data
     */
    public function newUserActivity($activity, array $data = array())
    {
        $data['php_req_id'] = $this->getConfigs()->get('req_id');

        $userActivity = new UserActivity();
        $userActivity->setActivity($activity);
        $userActivity->setCreatedAt(new \DateTime());
        $userActivity->setData(json_encode($data));
        $userActivity->setUser($this->getCurrentUser());

        $this->getEntityManager()->persist($userActivity);
        $this->getEntityManager()->flush();
    }
}
