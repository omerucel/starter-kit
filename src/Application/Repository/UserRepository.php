<?php

namespace Application\Repository;

use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository
{
    /**
     * @param $key
     * @return bool
     */
    public function hasPermission($key)
    {
        $sql = 'SELECT COUNT(p.id) FROM Application\Entity\User u'
            . ' JOIN u.role r'
            . ' JOIN r.rolePermissions rp'
            . ' JOIN rp.permission p'
            . ' WHERE p.name = :key';

        return $this->getEntityManager()->createQuery($sql)
            ->setParameter('key', $key)
            ->getSingleScalarResult() > 0;
    }

    /**
     * @param $username
     * @return User
     */
    public function findOneByUsernameOrEmail($username)
    {
        $sql = 'SELECT u FROM Application\Entity\User u'
            . ' WHERE u.username = :username OR u.email = :username';

        try {
            return $this->getEntityManager()->createQuery($sql)
                ->setParameter('username', $username)
                ->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }
}
