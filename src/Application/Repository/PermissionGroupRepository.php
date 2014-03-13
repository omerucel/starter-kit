<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class PermissionGroupRepository extends EntityRepository
{
    /**
     * @param $name
     * @param int $exceptId
     * @return bool
     */
    public function isNameUsing($name, $exceptId = 0)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup')->createQueryBuilder('pg');
        $qb->select('COUNT(pg.id)')->where('pg.name = :name');

        if ($exceptId > 0) {
            $qb->andWhere($qb->expr()->not('pg.id = :id'));
            $qb->setParameter(':id', $exceptId);
        }

        $qb->setParameter(':name', $name);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
