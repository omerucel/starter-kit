<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class PermissionRepository extends EntityRepository
{
    /**
     * @param $name
     * @param int $exceptId
     * @return bool
     */
    public function isNameUsing($name, $exceptId = 0)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Permission')->createQueryBuilder('p');
        $qb->select('COUNT(p.id)')->where('p.name = :name');

        if ($exceptId > 0) {
            $qb->andWhere($qb->expr()->not('p.id = :id'));
            $qb->setParameter(':id', $exceptId);
        }

        $qb->setParameter(':name', $name);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param array $ids
     * @return int
     */
    public function checkIdsCount(array $ids)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Permission')
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)');

        return $qb->where($qb->expr()->in('p.id', $ids))->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findIds(array $ids)
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Permission')
            ->createQueryBuilder('p')
            ->select('p');

        return $qb->where($qb->expr()->in('p.id', $ids))->getQuery()->getResult();
    }
}
