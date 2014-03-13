<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="permission_group_permission",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="permission_group_permission_UNIQUE",
 *              columns={"group_id", "permission_id"}
 *          )
 *      }
 * )
 * @ORM\Entity
 */
class PermissionGroupPermission
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
     * @var PermissionGroup
     *
     * @ORM\ManyToOne(targetEntity="PermissionGroup", inversedBy="groupPermissions", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $group;

    /**
     * @var Permission
     *
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="groupPermissions", cascade={"persist"})
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $permission;

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
     * @param Permission $permission
     */
    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param PermissionGroup $group
     */
    public function setGroup(PermissionGroup $group)
    {
        $this->group = $group;
    }

    /**
     * @return PermissionGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
