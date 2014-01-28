<?php

namespace Application;

abstract class ModuleAbstract
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @param $controllerClass
     * @param string $requestMethod
     * @param array $params
     * @return mixed
     */
    abstract public function dispatch($controllerClass, $requestMethod = 'get', array $params = array());

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }
}
