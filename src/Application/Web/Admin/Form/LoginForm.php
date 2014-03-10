<?php

namespace Application\Web\Admin\Form;

use MiniFrame\Extra\Service\RecaptchaService;
use MiniFrame\WebApplication\BaseForm;
use Respect\Validation\Validator;

class LoginForm extends BaseForm
{
    public $username;

    public $password;

    public function loadParamsFromRequest()
    {
        $this->username = $this->getRequest()->get('username');
        $this->password = $this->getRequest()->get('password');
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
        if (!$this->getRecaptcha()->check()->isValid()) {
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

    /**
     * @return \Captcha\Captcha
     */
    public function getRecaptcha()
    {
        return $this->getRecaptchaService()->getCaptcha();
    }

    /**
     * @return RecaptchaService
     */
    public function getRecaptchaService()
    {
        return $this->getServiceLoader()->getService('recaptcha');
    }
}
