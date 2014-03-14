<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    /**
     * @param $name
     * @param int $exceptId
     * @return bool
     */
    public function isNameUsing($name, $exceptId = 0)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Role')->createQueryBuilder('r');
        $qb->select('COUNT(r.id)')->where('r.name = :name');

        if ($exceptId > 0) {
            $qb->andWhere($qb->expr()->not('r.id = :id'));
            $qb->setParameter(':id', $exceptId);
        }

        $qb->setParameter(':name', $name);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
