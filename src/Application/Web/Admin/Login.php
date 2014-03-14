<?php

namespace Application\Web\Admin;

use Application\Web\Admin\Form\LoginForm;

class Login extends BaseController
{
    public function get()
    {
        $form = new LoginForm($this->getServiceLoader());

        $templateParams = array(
            'form' => $form,
        );

        return $this->renderPage($templateParams);
    }

    public function post()
    {
        $templateParams = array();

        $form = new LoginForm($this->getServiceLoader());
        $form->loadParamsFromRequest();

        if ($form->isValid()) {
            $user = $this->getAuthService()->authenticate($form->username, $form->password);

            if ($user != null) {
                // TODO : checkPermission oturum açma işleminden önce çalışmalı.
                $this->getAuthService()->login($user);

                $this->getAuthService()->checkPermission('admin.login');
                $this->getAuthService()->newUserActivity('admin.login');

                return $this->redirect('/admin');
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

        return $this->renderPage($templateParams);
    }

    /**
     * @param array $templateParams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderPage(array $templateParams = array())
    {
        $templateParams['recaptcha_public_key'] = $this->getConfigs()->get('recaptcha.public_key');
        return $this->render('admin/login.twig', $templateParams);
    }
}
