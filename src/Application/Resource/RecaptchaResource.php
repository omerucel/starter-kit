<?php

namespace Application\Resource;

use Captcha\Captcha;

trait RecaptchaResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return Captcha
     */
    public function getCaptcha()
    {
        if (!ResourceMemory::hasKey('recaptcha')) {
            $captchaConfigs = $this->getConfigs()['recaptcha'];

            $captcha = new Captcha();
            $captcha->setPublicKey($captchaConfigs['public_key']);
            $captcha->setPrivateKey($captchaConfigs['private_key']);

            ResourceMemory::set('recaptcha', $captcha);
        }

        return ResourceMemory::get('recaptcha');
    }
}
