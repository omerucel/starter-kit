<?php

namespace Application\Resource;

trait ConfigResource
{
    /**
     * @return array
     */
    public function getConfigs()
    {
        return ResourceMemory::get('configs', array());
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs)
    {
        ResourceMemory::set('configs', $configs);
    }
}
