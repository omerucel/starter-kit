<?php

namespace Application\Web\Admin;

use Application\Entity\Role;
use Application\Entity\RolePermission;
use Application\Repository\PermissionRepository;
use Application\Web\Admin\Form\RoleSaveForm;
use Doctrine\Common\Collections\ArrayCollection;

class RoleSave extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.roles.show');

        $form = new RoleSaveForm($this->getServiceLoader());
        $form->loadParamsFromCurrentRole();

        $templateParams = array(
            'form' => $form
        );

        return $this->render('admin/roles/save.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.roles.save');
        $templateParams = array();

        $form = new RoleSaveForm($this->getServiceLoader());
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            $isNew = false;
            $role = $form->getCurrentRole();
            if ($role == null) {
                $role = new Role();
                $isNew = true;
            }

            // Seçili izinleri kullanarak, ilgili role eklenmemiş izinleri ve silinecek izinleri bul.
            $selectedPermissions = $this->getPermissionRepository()->findIds($form->permissions);
            $newPermissions = $this->getNewPermissions($role, $selectedPermissions);
            $oldRolePermissions = $this->getDeletedRolePermissions($role, $selectedPermissions);

            // Yeni izinleri role ekle.
            foreach ($newPermissions as $permission) {
                $rolePermission = new RolePermission();
                $rolePermission->setPermission($permission);
                $rolePermission->setRole($role);
                $role->getRolePermissions()->add($rolePermission);
            }

            // Eski izinler siliniyor.
            if ($oldRolePermissions->count() > 0) {
                foreach ($oldRolePermissions as $rolePermission) {
                    $role->getRolePermissions()->removeElement($rolePermission);
                    $this->getEntityManager()->remove($rolePermission);
                }
            }

            // Değişiklikleri veritabanına gönder.
            $role->setName($form->name);
            $this->getEntityManager()->persist($role);
            $this->getEntityManager()->flush();

            // Yapılan işlem kayıt altına alınıyor.
            $this->getAuthService()
                ->newUserActivity('admin.roles.save', array('id' => $role->getId(), 'isNew' => $isNew));

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
        return $this->render('admin/roles/save.twig', $templateParams);
    }

    /**
     * @param Role $role
     * @param ArrayCollection $selectedPermissions
     * @return ArrayCollection
     */
    protected function getNewPermissions(Role $role, ArrayCollection $selectedPermissions)
    {
        if ($role->getId() > 0) {
            /**
             * @var RolePermission $rolePermission
             */
            $rolePermissions = new ArrayCollection();
            foreach ($role->getRolePermissions() as $rolePermission) {
                $rolePermissions->add($rolePermission->getPermission());
            }

            return $selectedPermissions->filter(
                function ($permission) use ($rolePermissions) {
                    return !$rolePermissions->contains($permission);
                }
            );
        } else {
            return $selectedPermissions;
        }
    }

    /**
     * @param Role $role
     * @param ArrayCollection $selectedPermissions
     * @return ArrayCollection
     */
    protected function getDeletedRolePermissions(Role $role, ArrayCollection $selectedPermissions)
    {
        if ($role->getId() > 0) {
            return $role->getRolePermissions()->filter(
                function ($rolePermission) use ($selectedPermissions) {
                    /**
                     * @var RolePermission $rolePermission
                     */
                    return !$selectedPermissions->contains($rolePermission->getPermission());
                }
            );
        } else {
            return new ArrayCollection();
        }
    }

    /**
     * @return PermissionRepository
     */
    protected function getPermissionRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Permission');
    }
}
