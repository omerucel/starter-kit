<?php

namespace MiniFrame\Extra\Service;

use Application\Entity\User;
use Application\Entity\UserActivity;
use Application\Repository\UserRepository;
use MiniFrame\BaseService;
use MiniFrame\Extra\Service\AuthService\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthService extends BaseService
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * @param $username
     * @param $password
     * @return User|null
     */
    public function authenticate($username, $password)
    {
        $user = $this->getUserRepository()->findOneByUsernameOrEmail($username);
        if ($user == null || !$user->passwordVerify($password)) {
            return null;
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function login(User $user)
    {
        $this->getSession()->set('user_id', $user->getId());
        $this->currentUser = $user;
    }

    public function logout()
    {
        $this->getSession()->remove('user_id');
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        $userId = $this->getSession()->get('user_id', 0);
        if ($userId > 0) {
            $this->currentUser = $this->getUserRepository()->find($userId);
        }

        return $this->currentUser;
    }

    /**
     * @param $key
     * @throws AccessDeniedException
     */
    public function checkPermission($key)
    {
        if (!$this->hasPermission($key)) {
            throw new AccessDeniedException('User does not have ' . $key . ' permission.');
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasPermission($key)
    {
        return $this->getUserRepository()->hasPermission($key);
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

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        /**
         * @var SessionHandlerService $sessionHandlerService
         */
        $sessionHandlerService = $this->getService('session_handler');
        return $sessionHandlerService->getSession();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        /**
         * @var DoctrineService $doctrineService
         */
        $doctrineService = $this->getService('doctrine');
        return $doctrineService->getEntityManager();
    }
}
