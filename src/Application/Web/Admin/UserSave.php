<?php

namespace Application\Web\Admin;

use Application\Entity\User;
use Application\Web\Admin\Form\UserSaveForm;
use Application\Web\Exception\RecordNotFound;

class UserSave extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.users.show');

        $form = new UserSaveForm($this->getServiceLoader());
        $form->loadParamsFromCurrentUser();

        if ($form->id > 0 && $form->getCurrentUser() == null) {
            throw new RecordNotFound(sprintf('%d ID numaralı kullanıcı sistemde bulunamadı.', $form->id));
        }

        $templateParams = array(
            'form' => $form
        );

        return $this->render('admin/users/save.twig', $templateParams);
    }

    public function post()
    {
        $this->getAuthService()->checkPermission('admin.users.save');
        $templateParams = array();

        $form = new UserSaveForm($this->getServiceLoader());
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            $isNew = false;
            $user = $form->getCurrentUser();
            if ($user == null) {
                $user = new User();
                $isNew = true;
            }

            $user->setUsername($form->username);
            $user->setEmail($form->email);
            $user->setName($form->name);
            $user->setSurname($form->surname);
            $user->setRole($form->getCurrentRole());

            if ($form->isPasswordChanged()) {
                $user->setPassword($form->password);
            }

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            // Yapılan işlem kayıt altına alınıyor.
            $this->getAuthService()
                ->newUserActivity('admin.users.save', array('id' => $user->getId(), 'isNew' => $isNew));

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
        return $this->render('admin/users/save.twig', $templateParams);
    }
}
