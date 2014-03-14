<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="role",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="name_UNIQUE",
 *              columns={"name"}
 *          )
 *      }
 * )
 * @ORM\Entity(repositoryClass="Application\Repository\RoleRepository")
 */
class Role
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
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RolePermission", mappedBy="role", cascade={"persist"})
     */
    private $rolePermissions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="role", cascade={"persist"})
     */
    private $users;

    public function __construct()
    {
        $this->rolePermissions = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @param \Doctrine\Common\Collections\ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users)
    {
        $this->users = $users;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
