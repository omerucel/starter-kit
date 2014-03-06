<?php

namespace MiniFrame\Extra\Service;

use Captcha\Captcha;
use MiniFrame\BaseService;

class RecaptchaService extends BaseService
{
    /**
     * @var Captcha
     */
    protected $recaptcha;

    /**
     * @return Captcha
     */
    public function getCaptcha()
    {
        if ($this->recaptcha == null) {
            $this->recaptcha = new Captcha();
            $this->recaptcha->setPublicKey($this->getConfigs()->get('recaptcha.public_key'));
            $this->recaptcha->setPrivateKey($this->getConfigs()->get('recaptcha.private_key'));
        }

        return $this->recaptcha;
    }
}
