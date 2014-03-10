<?php

namespace MiniFrame;

abstract class BaseApplication
{
    /**
     * @var ServiceLoader
     */
    protected $serviceLoader;

    /**
     * @param ServiceLoader $serviceLoader
     */
    public function __construct(ServiceLoader $serviceLoader)
    {
        $this->serviceLoader = $serviceLoader;
    }

    /**
     * Ayarlara erişmek için yardımcı metod.
     *
     * @return Config
     */
    public function getConfigs()
    {
        return $this->getServiceLoader()->getConfigs();
    }

    /**
     * @return \MiniFrame\ServiceLoader
     */
    public function getServiceLoader()
    {
        return $this->serviceLoader;
    }

    abstract public function serve();
}
