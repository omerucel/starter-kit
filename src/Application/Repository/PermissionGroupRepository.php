<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class PermissionGroupRepository extends EntityRepository
{
    /**
     * @param $name
     * @return bool
     */
    public function isNameUsing($name)
    {
        $sql = 'SELECT COUNT(pg.id) FROM \Application\Entity\PermissionGroup pg'
            . ' WHERE pg.name = :name';

        return $this->getEntityManager()->createQuery($sql)
            ->setParameter('name', $name)
            ->getSingleScalarResult() > 0;
    }
}
