<?php

namespace Application\Web\Admin\Form;

use Application\Entity\PermissionGroup;
use Application\Entity\PermissionGroupPermission;
use Application\Repository\PermissionGroupRepository;
use Application\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use MiniFrame\ServiceLoader;
use MiniFrame\WebApplication\BaseForm;
use Respect\Validation\Validator;

class PermissionGroupSaveForm extends BaseForm
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
    public $allPermissions;

    /**
     * @var PermissionGroup
     */
    protected $currentGroup;

    public function __construct(ServiceLoader $serviceLoader)
    {
        parent::__construct($serviceLoader);
        $this->allPermissions = $this->getPermissionRepository()->findAll();
    }

    public function loadParamsFromRequest()
    {
        $this->id = intval($this->getRequest()->get('id'));
        $this->name = $this->getRequest()->get('name');

        $this->permissions = $this->getRequest()->get('permissions', array());
        if (!is_array($this->permissions)) {
            $this->permissions = array();
        }
    }

    public function loadParamsFromCurrentGroup()
    {
        $this->id = intval($this->getRequest()->get('id'));

        if ($this->getCurrentGroup() != null) {
            $this->name = $this->getCurrentGroup()->getName();

            /**
             * @var PermissionGroupPermission $groupPermission
             */
            foreach ($this->getCurrentGroup()->getGroupPermissions() as $groupPermission) {
                $this->permissions[] = $groupPermission->getPermission()->getId();
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
            $this->setMessage('name_empty', 'Grup adı gerekli.');
        } else {
            if ($this->getPermissionGroupRepository()->isNameUsing($this->name, $this->id)) {
                $this->setMessage('name_using', 'Seçtiğiniz grup adı kullanılıyor.');
            }
        }
    }

    protected function validatePermissions()
    {
        if (empty($this->permissions)) {
            $this->setMessage('permissions_empty', 'Lütfen bir izin seçiniz.');
        } else {
            if ($this->getPermissionRepository()->checkIdsCount($this->permissions) != count($this->permissions)) {
                $this->setMessage('permission_count_fail', 'Lütfen izinleri doğru bir şekilde seçiniz.');
            }
        }
    }

    /**
     * @return PermissionGroup
     */
    public function getCurrentGroup()
    {
        if ($this->currentGroup == null) {
            $this->currentGroup = $this->getPermissionGroupRepository()->find($this->id);
        }

        return $this->currentGroup;
    }

    /**
     * @return PermissionRepository
     */
    public function getPermissionRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Permission');
    }

    /**
     * @return PermissionGroupRepository
     */
    public function getPermissionGroupRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup');
    }

    public function clear()
    {
        $this->id = 0;
        $this->name = '';
        $this->permissions = array();
    }
}
