<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="permission",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="name_UNIQUE",
 *              columns={"name"}
 *          )
 *      }
 * )
 * @ORM\Entity(repositoryClass="Application\Repository\PermissionRepository")
 */
class Permission
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RolePermission", mappedBy="permission", cascade={"persist"})
     */
    private $rolePermissions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PermissionGroup", mappedBy="permission", cascade={"persist"})
     */
    private $groupPermissions;

    public function __construct()
    {
        $this->rolePermissions = new ArrayCollection();
        $this->groupPermissions = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $rolePermissions
     */
    public function setRolePermissions(ArrayCollection $rolePermissions)
    {
        $this->rolePermissions = $rolePermissions;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRolePermissions()
    {
        return $this->rolePermissions;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $groupPermissions
     */
    public function setGroupPermissions(ArrayCollection $groupPermissions)
    {
        $this->groupPermissions = $groupPermissions;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGroupPermissions()
    {
        return $this->groupPermissions;
    }
}
