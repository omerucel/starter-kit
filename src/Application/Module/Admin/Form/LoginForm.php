<?php

namespace Application\Module\Admin\Form;

use Application\Module\BaseForm;
use Application\Resource\ConfigResource;
use Application\Resource\HttpResource;
use Application\Resource\RecaptchaResource;
use Symfony\Component\Validator\Constraints as Assert;

class LoginForm extends BaseForm
{
    use ConfigResource;
    use RecaptchaResource;

    /**
     * @Assert\NotBlank(message = "Kullanıcı adı gerekli!")
     */
    protected $username;

    /**
     * @Assert\NotBlank(message = "Şifre gerekli!")
     */
    protected $password;

    /**
     * @var string
     */
    protected $recaptchaPublicKey;

    /**
     * @Assert\True(message = "Geçersiz güvenlik kodu")
     */
    public function isValidSecurityCode()
    {
        return $this->getCaptcha()->check()->isValid();
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $recaptchaPublicKey
     */
    public function setRecaptchaPublicKey($recaptchaPublicKey)
    {
        $this->recaptchaPublicKey = $recaptchaPublicKey;
    }

    /**
     * @return mixed
     */
    public function getRecaptchaPublicKey()
    {
        return $this->recaptchaPublicKey;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
}
