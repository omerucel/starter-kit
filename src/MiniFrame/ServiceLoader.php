<?php

namespace MiniFrame;

class ServiceLoader
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $services;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->services = array();
    }

    /**
     * @return Config
     */
    public function getConfigs()
    {
        return $this->config;
    }

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
     * @param $name
     * @param $object
     */
    public function setService($name, $object)
    {
        $this->services[$name] = $object;
    }
}
