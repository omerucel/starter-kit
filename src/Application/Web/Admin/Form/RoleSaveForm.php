<?php

namespace Application\Web\Admin\Form;

use Application\Entity\PermissionGroup;
use Application\Entity\PermissionGroupPermission;
use Application\Entity\Role;
use Application\Entity\RolePermission;
use Application\Repository\PermissionGroupRepository;
use Application\Repository\PermissionRepository;
use Application\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use MiniFrame\WebApplication\BaseForm;
use Respect\Validation\Validator;

class RoleSaveForm extends BaseForm
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $permissions;

    /**
     * @var ArrayCollection
     */
    protected $allPermissionGroups;

    /**
     * @var Role
     */
    protected $currentRole;

    public function loadParamsFromRequest()
    {
        $this->id = intval($this->getRequest()->get('id'));
        $this->name = $this->getRequest()->get('name');
        $this->permissions = $this->getRequest()->get('permissions', array());
    }

    public function loadParamsFromCurrentRole()
    {
        $this->id = intval($this->getRequest()->get('id'));

        if ($this->getCurrentRole() != null) {
            $this->name = $this->getCurrentRole()->getName();

            /**
             * @var RolePermission $rolePermission
             */
            foreach ($this->getCurrentRole()->getRolePermissions() as $rolePermission) {
                $this->permissions[] = $rolePermission->getPermission()->getId();
            }
        }
    }

    /**
     * @return bool
     */
    protected function validateFields()
    {
        $this->validateName();
        $this->validatePermissions();
    }

    protected function validateName()
    {
        if (!Validator::create()->notEmpty()->validate($this->name)) {
            $this->setMessage('name', 'empty', 'Rol adı gerekli.');
        } else {
            if ($this->getRoleRepository()->isNameUsing($this->name, $this->id)) {
                $this->setMessage('name', 'using', 'Seçtiğiniz rol adı kullanılıyor.');
            }
        }
    }

    protected function validatePermissions()
    {
        if (empty($this->permissions)) {
            $this->setMessage('permissions', 'empty', 'Lütfen bir izin seçiniz.');
        } else {
            if ($this->getPermissionRepository()->checkIdsCount($this->permissions) != count($this->permissions)) {
                $this->setMessage('permission', 'count_fail', 'Lütfen izinleri doğru bir şekilde seçiniz.');
            }
        }
    }

    /**
     * @return Role
     */
    public function getCurrentRole()
    {
        if ($this->currentRole == null) {
            $this->currentRole = $this->getRoleRepository()->find($this->id);
        }

        return $this->currentRole;
    }

    /**
     * @return array
     */
    public function getAllPermissionGroups()
    {
        if ($this->allPermissionGroups == null) {
            $groups = $this->getPermissionGroupRepository()->findBy(array(), array('name' => 'asc'));
            $permissions = array();

            /**
             * @var PermissionGroup $group
             * @var PermissionGroupPermission $groupPermission
             */
            foreach ($groups as $group) {
                $item = array(
                    'name' => $group->getName(),
                    'permissions' => array()
                );

                foreach ($group->getGroupPermissions() as $groupPermission) {
                    $permId = $groupPermission->getPermission()->getId();
                    $permName = $groupPermission->getPermission()->getName();
                    $item['permissions'][] = array(
                        'id' => $permId,
                        'name' => $permName
                    );
                }

                $permissions[] = $item;
            }

            $this->allPermissionGroups = $permissions;
        }

        return $this->allPermissionGroups;
    }

    /**
     * @return PermissionGroupRepository
     */
    public function getPermissionGroupRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup');
    }

    /**
     * @return PermissionRepository
     */
    public function getPermissionRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Permission');
    }

    /**
     * @return RoleRepository
     */
    public function getRoleRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Role');
    }

    public function clear()
    {
        $this->id = 0;
        $this->name = '';
        $this->permissions = array();
    }
}
