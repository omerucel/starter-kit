<?php

namespace Application\Web\Admin\Form;

use Application\Entity\Permission;
use Application\Repository\PermissionRepository;
use MiniFrame\WebApplication\BaseForm;
use Respect\Validation\Validator;

class PermissionSaveForm extends BaseForm
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
     * @var Permission
     */
    protected $currentPermission;

    public function loadParamsFromRequest()
    {
        $this->id = intval($this->getRequest()->get('id'));
        $this->name = $this->getRequest()->get('name');
    }

    public function loadParamsFromCurrentPermission()
    {
        $this->id = intval($this->getRequest()->get('id'));

        if ($this->getCurrentPermission() != null) {
            $this->name = $this->getCurrentPermission()->getName();
        }
    }

    /**
     * @return bool
     */
    protected function validateFields()
    {
        $this->validateName();
    }

    protected function validateName()
    {
        if (!Validator::create()->notEmpty()->validate($this->name)) {
            $this->setMessage('name_empty', 'İzin adı gerekli.');
        } else {
            if ($this->getPermissionRepository()->isNameUsing($this->name)) {
                $this->setMessage('name_using', 'Seçtiğiniz izin adı kullanılıyor.');
            }
        }
    }

    /**
     * @return Permission
     */
    public function getCurrentPermission()
    {
        if ($this->currentPermission == null) {
            $this->currentPermission = $this->getPermissionRepository()->find($this->id);
        }

        return $this->currentPermission;
    }

    /**
     * @return PermissionRepository
     */
    public function getPermissionRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Permission');
    }

    public function clear()
    {
        $this->id = 0;
        $this->name = '';
    }
}
