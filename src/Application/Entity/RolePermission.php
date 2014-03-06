<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="role_permission",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="role_permission_UNIQUE",
 *              columns={"role_id", "permission_id"}
 *          )
 *      }
 * )
 * @ORM\Entity
 */
class RolePermission
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
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="rolePermissions", cascade={"persist"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $role;

    /**
     * @var Permission
     *
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="rolePermissions", cascade={"persist"})
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
     * @param \Application\Entity\Permission $permission
     */
    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return \Application\Entity\Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param \Application\Entity\Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return \Application\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
