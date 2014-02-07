<?php

namespace Application\Module\Admin;

use Application\Entity\AdminUserQuery;
use Application\Module\Admin\Form\LoginForm;
use Application\Resource\AnnotationResource;
use Application\Resource\TwigResource;

class Login extends BaseAdminController
{
    use AnnotationResource;
    use TwigResource;

    public function get()
    {
        $templateParams = [
            'form' => $this->getForm()
        ];
        return $this->render('admin/login.twig', $templateParams);
    }

    public function post()
    {
        $templateParams = [];

        $form = $this->getForm();
        $form->setParams($this->getRequest()->request->all());
        if ($form->isValid()) {
            $user = AdminUserQuery::create()
                ->filterByUsername($form->getUsername())
                ->filterByPassword(sha1($form->getPassword()))
                ->findOne();

            if ($user != null) {
                $this->getSession()->set('user', $user->toArray());
                $this->getSession()->set('is_admin_logged_in', 1);
                return $this->redirect('/admin');
            } else {
                $templateParams['message'] = 'Bilgiler geçersiz.';
            }
        } else {
            $templateParams['message'] = 'Eksik bilgi gönderildi.';
        }

        $templateParams['message_type'] = 'danger';
        $templateParams['form'] = $form;
        return $this->render('admin/login.twig', $templateParams);
    }

    /**
     * @return LoginForm
     */
    protected function getForm()
    {
        $form = new LoginForm();
        $form->setRecaptchaPublicKey($this->getConfigs()['recaptcha']['public_key']);

        return $form;
    }
}
