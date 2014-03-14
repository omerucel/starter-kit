<?php

namespace Application\Web\Admin;


use Application\Entity\Permission;
use Application\Entity\PermissionGroup;
use Application\Entity\PermissionGroupPermission;
use Application\Repository\PermissionGroupRepository;
use Application\Repository\PermissionRepository;
use Application\Web\Admin\Form\PermissionGroupSaveForm;
use Doctrine\Common\Collections\ArrayCollection;

class PermissionGroupSave extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.permissions.group.show');

        $form = new PermissionGroupSaveForm($this->getServiceLoader());
        $form->loadParamsFromCurrentGroup();

        $templateParams = array(
            'form' => $form
        );

        return $this->render('admin/permission-groups/save.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.permissions.group.save');
        $templateParams = array();

        $form = new PermissionGroupSaveForm($this->getServiceLoader());
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            $group = $form->getCurrentGroup();
            if ($group == null) {
                $group = new PermissionGroup();
            }

            $group->setName($form->name);

            // Seçili izinleri kullanarak, ilgili gruba eklenmemiş izinleri ve silinecek izinleri bul.
            $selectedPermissions = $this->getPermissionRepository()->findIds($form->permissions);
            $newPermissions = $this->getNewPermissions($group, $selectedPermissions);
            $oldGroupPermissions = $this->getDeletedGroupPermissions($group, $selectedPermissions);

            // Yeni izinleri gruba ekle.
            foreach ($newPermissions as $permission) {
                $permissionGroupPermission = new PermissionGroupPermission();
                $permissionGroupPermission->setPermission($permission);
                $permissionGroupPermission->setGroup($group);
                $group->getGroupPermissions()->add($permissionGroupPermission);
            }

            // Eski izinler siliniyor.
            if ($oldGroupPermissions->count() > 0) {
                foreach ($oldGroupPermissions as $groupPermission) {
                    $group->getGroupPermissions()->removeElement($groupPermission);
                    $this->getEntityManager()->remove($groupPermission);
                }
            }

            // Değişiklikler veritabanına aktarılıyor.
            $this->getEntityManager()->persist($group);
            $this->getEntityManager()->flush();

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
        return $this->render('admin/permission-groups/save.twig', $templateParams);
    }

    /**
     * @param PermissionGroup $group
     * @param ArrayCollection $selectedPermissions
     * @return ArrayCollection
     */
    protected function getNewPermissions(PermissionGroup $group, ArrayCollection $selectedPermissions)
    {
        if ($group->getId() > 0) {
            /**
             * @var PermissionGroupPermission $groupPermission
             */
            $groupPermissions = new ArrayCollection();
            foreach ($group->getGroupPermissions() as $groupPermission) {
                $groupPermissions->add($groupPermission->getPermission());
            }

            return $selectedPermissions->filter(
                function ($permission) use ($groupPermissions) {
                    return !$groupPermissions->contains($permission);
                }
            );
        } else {
            return $selectedPermissions;
        }
    }

    /**
     * @param PermissionGroup $group
     * @param ArrayCollection $selectedPermissions
     * @return ArrayCollection
     */
    protected function getDeletedGroupPermissions(PermissionGroup $group, ArrayCollection $selectedPermissions)
    {
        if ($group->getId() > 0) {
            return $group->getGroupPermissions()->filter(
                function ($groupPermission) use ($selectedPermissions) {
                    /**
                     * @var PermissionGroupPermission $groupPermission
                     */
                    return !$selectedPermissions->contains($groupPermission->getPermission());
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

    /**
     * @return PermissionGroupRepository
     */
    protected function getPermissionGroupRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\PermissionGroup');
    }
}
