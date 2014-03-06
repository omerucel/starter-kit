<?php

namespace Application\Web\Admin;

use Application\Entity\UserRepository;
use Application\Web\Admin\Form\LoginForm;

class Login extends BaseController
{
    public function get()
    {
        $form = new LoginForm($this);

        $templateParams = array(
            'form' => $form,
            'recaptcha_public_key' => $this->getConfigs()->get('recaptcha.public_key')
        );
        return $this->render('admin/login.twig', $templateParams);
    }

    public function post()
    {
        $templateParams = [];

        $form = new LoginForm($this);
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            /**
             * @var UserRepository $userRepository
             */
            $userRepository = $this->getEntityManager()->getRepository('Application\Entity\User');
            $user = $userRepository->findOneByUsernameOrEmail($form->username);

            if ($user != null && $user->passwordVerify($form->password)) {
                $this->currentUser = $user; // TODO : Change with setter.

                if ($this->hasPermission('admin.login')) {
                    $this->newUserActivity('admin.login');
                    $this->getSession()->set('admin_user_id', $user->getId());
                    return $this->redirect('/admin');
                } else {
                    $templateParams['message'] = 'Yetkisiz erişim.';
                    $this->getDefaultLogger()->warning('Yetkisiz erişim.', array('username' => $user->getUsername()));
                }
            } else {
                $this->getDefaultLogger()->warning(
                    'Geçersiz bilgilerle giriş yapılmaya çalışıldı.',
                    array(
                        'header' => $this->getRequest()->headers->all(),
                        'server' => $this->getRequest()->server->all(),
                        'params' => $this->getRequest()->request->all()
                    )
                );
                $templateParams['message'] = 'Bilgiler geçersiz.';
            }
        } else {
            $templateParams['message'] = 'Bilgiler doğrulanamadı.';
        }

        $templateParams['message_type'] = 'danger';
        $templateParams['form'] = $form;
        $templateParams['recaptcha_public_key'] = $this->getConfigs()->get('recaptcha.public_key');
        return $this->render('admin/login.twig', $templateParams);
    }
}
