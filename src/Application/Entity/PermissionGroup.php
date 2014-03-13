<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="permission_group")
 * @ORM\Entity(repositoryClass="Application\Repository\PermissionGroupRepository")
 */
class PermissionGroup
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PermissionGroupPermission", mappedBy="group", cascade={"persist"})
     */
    private $groupPermissions;

    public function __construct()
    {
        $this->groupPermissions = new ArrayCollection();
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
}
