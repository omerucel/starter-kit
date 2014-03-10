<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class PermissionRepository extends EntityRepository
{
    /**
     * @param $name
     * @return bool
     */
    public function isNameUsing($name)
    {
        $sql = 'SELECT COUNT(p.id) FROM \Application\Entity\Permission p'
            . ' WHERE p.name = :name';

        return $this->getEntityManager()->createQuery($sql)
            ->setParameter('name', $name)
            ->getSingleScalarResult() > 0;
    }
}
