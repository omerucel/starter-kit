<?php

namespace Application\Resource;

trait FacebookResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \Facebook
     */
    public function getFacebook()
    {
        if (!ResourceMemory::hasKey('facebook')) {
            $facebookConfigs = $this->getConfigs()['facebook'];

            $facebook = new \Facebook(
                array(
                    'appId' => $facebookConfigs['app_id'],
                    'secret' => $facebookConfigs['app_secret']
                )
            );

            ResourceMemory::set('facebook', $facebook);
        }

        return ResourceMemory::get('facebook');
    }
}
