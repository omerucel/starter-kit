<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;

class MemcachedService extends BaseService
{
    /**
     * @var \Memcached
     */
    protected $memcached;

    /**
     * @return \Memcached
     */
    public function getMemcached()
    {
        if ($this->memcached == null) {
            $this->memcached = new \Memcached();
            $this->memcached->addServers($this->getConfigs()->getArray('memcached.servers'));
        }

        return $this->memcached;
    }
}
