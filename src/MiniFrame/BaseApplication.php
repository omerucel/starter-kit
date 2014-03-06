<?php

namespace MiniFrame;

abstract class BaseApplication
{
    /**
     * @var array
     */
    protected $configData;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $services;

    /**
     * @param array $configData
     */
    public function __construct(array $configData = array())
    {
        $this->configData = $configData;
        $this->services = array();
    }

    abstract public function serve();

    /**
     * @param $name
     * @return BaseService
     * @throws \Exception
     */
    public function getService($name)
    {
        if (!isset($this->services[$name])) {
            $serviceClass = $this->getConfigs()->get('services.' . $name);
            if (!$serviceClass) {
                throw new \Exception('Service is not registered : ' . $name);
            }

            $service = new $serviceClass($this);
            $this->services[$name] = $service;
        }

        return $this->services[$name];
    }

    /**
     * @return Config
     */
    public function getConfigs()
    {
        if ($this->config == null) {
            $this->config = new Config($this->configData);
        }

        return $this->config;
    }
}
