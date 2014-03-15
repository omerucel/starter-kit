<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class UserActivityRepository extends EntityRepository
{
    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteIds(array $ids)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\UserActivity')->createQueryBuilder('ua');
        return $qb->where($qb->expr()->in('ua.id', $ids))->delete()->getQuery()->execute();
    }
}
