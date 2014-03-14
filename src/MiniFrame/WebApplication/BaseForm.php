<?php

namespace MiniFrame\WebApplication;

use MiniFrame\Extra\Service\DoctrineService;
use MiniFrame\Extra\Service\HttpFoundationService;
use MiniFrame\ServiceLoader;

abstract class BaseForm
{
    /**
     * @var ServiceLoader
     */
    protected $serviceLoader;

    /**
     * @var array
     */
    public $messages = array();

    /**
     * @param ServiceLoader $serviceLoader
     */
    public function __construct(ServiceLoader $serviceLoader)
    {
        $this->serviceLoader = $serviceLoader;
        $this->messages = array();
    }

    /**
     * @return ServiceLoader
     */
    public function getServiceLoader()
    {
        return $this->serviceLoader;
    }

    /**
     * @param $group
     * @param $key
     * @param $value
     */
    public function setMessage($group, $key, $value)
    {
        if (!isset($this->messages[$group])) {
            $this->messages[$group] = array();
        }

        $this->messages[$group][$key] = $value;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return !empty($this->messages);
    }

    /**
     * @return bool
     */
    final public function isValid()
    {
        $this->validateFields();
        return !$this->hasMessage();
    }

    abstract public function loadParamsFromRequest();

    /**
     * @return bool
     */
    abstract protected function validateFields();

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->getHttpFoundationService()->getRequest();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrineService()->getEntityManager();
    }

    /**
     * @return HttpFoundationService
     */
    public function getHttpFoundationService()
    {
        return $this->getServiceLoader()->getService('http_foundation');
    }

    /**
     * @return DoctrineService
     */
    public function getDoctrineService()
    {
        return $this->getServiceLoader()->getService('doctrine');
    }
}
