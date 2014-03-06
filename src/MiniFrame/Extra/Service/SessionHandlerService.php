<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class SessionHandlerService extends BaseService
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var NativeSessionStorage
     */
    protected $storage;

    /**
     * @var \SessionHandlerInterface
     */
    protected $handler;

    /**
     * @return Session
     */
    public function getSession()
    {
        if ($this->session == null) {
            $storageOptions = (array)$this->getConfigs()->getArray('session.storage');
            $this->storage = new NativeSessionStorage($storageOptions, $this->getHandler());

            $this->session = new Session($this->storage);
            $this->session->start();
        }

        return $this->session;
    }

    /**
     * @return \SessionHandlerInterface
     */
    public function getHandler()
    {
        if ($this->handler == null) {
            $defaultHandler = $this->getConfigs()->get('session.default_handler');
            if ($defaultHandler == 'pdo') {
                $this->handler = $this->createPDOHandler();
            }
        }

        return $this->handler;
    }

    /**
     * @return PdoSessionHandler
     */
    protected function createPDOHandler()
    {
        /**
         * @var PdoService $pdoService
         */
        $pdoService = $this->getApplication()->getService('pdo');
        $pdo = $pdoService->getPdo();
        $pdoHandler = new PdoSessionHandler($pdo, $this->getConfigs()->getArray('session.handler.pdo'));
        return $pdoHandler;
    }
}
