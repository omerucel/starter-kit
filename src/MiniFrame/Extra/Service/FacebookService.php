<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;

class FacebookService extends BaseService
{
    /**
     * @var \Facebook
     */
    protected $facebook;

    /**
     * @return \Facebook
     */
    public function getFacebook()
    {
        if ($this->facebook == null) {
            $this->facebook = new \Facebook(
                array(
                    'appId' => $this->getConfigs()->get('facebook.app_id'),
                    'secret' => $this->getConfigs()->get('facebook.app_secret')
                )
            );
        }

        return $this->facebook;
    }
}
