<?php

namespace Application\Web\Admin\Form;

use Application\Web\BaseForm;
use MiniFrame\Extra\Service\RecaptchaService;
use Respect\Validation\Validator;

class LoginForm extends BaseForm
{
    public $username;

    public $password;

    public function loadParamsFromRequest()
    {
        $this->username = $this->getController()->getRequest()->get('username');
        $this->password = $this->getController()->getRequest()->get('password');
    }

    /**
     * @return bool
     */
    protected function validateFields()
    {
        $this->validateSecurityCode();
        $this->validateUsername();
        $this->validatePassword();
    }

    public function validateSecurityCode()
    {
        /**
         * @var RecaptchaService $recaptchaService
         */
        $recaptchaService = $this->getController()->getService('recaptcha');
        if (!$recaptchaService->getCaptcha()->check()->isValid()) {
            $this->setMessage('security_code', 'Geçersiz güvenlik kodu.');
        }
    }

    public function validateUsername()
    {
        if (!Validator::create()->notEmpty()->validate($this->username)) {
            $this->setMessage('username_empty', 'Kullanıcı adı gerekli.');
        }
    }

    public function validatePassword()
    {
        if (!Validator::create()->notEmpty()->validate($this->password)) {
            $this->setMessage('password_empty', 'Şifre gerekli.');
        }
    }
}
