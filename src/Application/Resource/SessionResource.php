<?php

namespace Application\Resource;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

trait SessionResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \PDO
     */
    abstract public function getPdo();

    /**
     * @return Session
     */
    public function getSession()
    {
        if (!ResourceMemory::hasKey('session')) {
            $sessionConfigs = $this->getConfigs()['session'];

            $pdoHandler = new PdoSessionHandler($this->getPdo(), $sessionConfigs);
            $storage = new NativeSessionStorage(array(), $pdoHandler);
            $storage->setOptions($sessionConfigs);

            $session = new Session($storage);
            $session->start();

            ResourceMemory::set('session', $session);
        }

        return ResourceMemory::get('session');
    }
}