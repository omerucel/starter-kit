<?php

namespace Application\Repository;

use Application\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository
{
    /**
     * @param $email
     * @param int $exceptId
     * @return bool
     */
    public function isEmailUsing($email, $exceptId = 0)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\User')->createQueryBuilder('u');
        $qb->select('COUNT(u.id)')->where('u.email = :email');

        if ($exceptId > 0) {
            $qb->andWhere($qb->expr()->not('u.id = :id'));
            $qb->setParameter(':id', $exceptId);
        }

        $qb->setParameter(':email', $email);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param $username
     * @param int $exceptId
     * @return bool
     */
    public function isUsernameUsing($username, $exceptId = 0)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\User')->createQueryBuilder('u');
        $qb->select('COUNT(u.id)')->where('u.username = :username');

        if ($exceptId > 0) {
            $qb->andWhere($qb->expr()->not('u.id = :id'));
            $qb->setParameter(':id', $exceptId);
        }

        $qb->setParameter(':username', $username);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param $userId
     * @param $key
     * @return bool
     */
    public function hasPermission($userId, $key)
    {
        $sql = 'SELECT COUNT(p.id) FROM Application\Entity\User u'
            . ' JOIN u.role r'
            . ' JOIN r.rolePermissions rp'
            . ' JOIN rp.permission p'
            . ' WHERE p.name = :key AND u.id = :id';

        return $this->getEntityManager()->createQuery($sql)
            ->setParameter('key', $key)
            ->setParameter('id', $userId)
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

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteIds(array $ids)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\User')->createQueryBuilder('u');
        return $qb->where($qb->expr()->in('u.id', $ids))->delete()->getQuery()->execute();
    }
}
