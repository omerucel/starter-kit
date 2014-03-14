<?php

namespace Application\Web\Admin;

use Application\Entity\Permission;
use Application\Web\Admin\Form\PermissionSaveForm;

class PermissionSave extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.permissions.show');

        $form = new PermissionSaveForm($this->getServiceLoader());
        $form->loadParamsFromCurrentPermission();

        $templateParams = array(
            'form' => $form
        );

        return $this->render('admin/permissions/save.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.permissions.save');
        $templateParams = array();

        $form = new PermissionSaveForm($this->getServiceLoader());
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            $isNew = false;
            $permission = $form->getCurrentPermission();
            if ($permission == null) {
                $permission = new Permission();
                $isNew = true;
            }

            $permission->setName($form->name);
            $this->getEntityManager()->persist($permission);
            $this->getEntityManager()->flush();

            // Yapılan işlem kayıt altına alınıyor.
            $this->getAuthService()
                ->newUserActivity('admin.permissions.save', array('id' => $permission->getId(), 'isNew' => $isNew));

            $templateParams['message'] = 'İşlem gerçekleşti.';
            $templateParams['message_type'] = 'success';

            if ($form->id == 0) {
                $form->clear();
            }
        } else {
            $templateParams['message'] = 'İşlem gerçekleşirken bazı sorunlar oluştu.';
            $templateParams['message_type'] = 'danger';
        }

        $templateParams['form'] = $form;
        return $this->render('admin/permissions/save.twig', $templateParams);
    }
}
