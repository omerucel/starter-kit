<?php

namespace MiniFrame\ConsoleApplication;

use MiniFrame\ServiceLoader;
use Symfony\Component\Console\Helper\Helper;

class ServiceLoaderHelper extends Helper
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
     * @param $name
     * @return \MiniFrame\BaseService
     */
    public function getService($name)
    {
        return $this->getServiceLoader()->getService($name);
    }

    /**
     * @return \MiniFrame\Config
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

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'service_loader';
    }
}
