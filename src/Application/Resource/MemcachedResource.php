<?php

namespace Application\Resource;

trait MemcachedResource
{
    /**
     * @return \Memcached
     */
    public function getMemcached()
    {
        if (!ResourceMemory::hasKey('memcached')) {
            $memcached = new \Memcached();
            $memcached->addServer('127.0.0.1', 11211);
            ResourceMemory::set('memcached', $memcached);
        }

        return ResourceMemory::get('memcached');
    }
}
