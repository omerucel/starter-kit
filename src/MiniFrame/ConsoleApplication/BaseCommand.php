<?php

namespace MiniFrame\ConsoleApplication;

use MiniFrame\Extra\Service\DoctrineService;
use MiniFrame\Extra\Service\MonologService;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrineService()->getEntityManager();
    }

    /**
     * @return \Monolog\Logger
     */
    public function getDefaultLogger()
    {
        return $this->getMonologService()->getDefaultLogger();
    }

    /**
     * @return MonologService
     */
    public function getMonologService()
    {
        return $this->getServiceLoaderHelper()->getService('monolog');
    }

    /**
     * @return DoctrineService
     */
    public function getDoctrineService()
    {
        return $this->getServiceLoaderHelper()->getService('doctrine');
    }

    /**
     * @return ServiceLoaderHelper
     */
    public function getServiceLoaderHelper()
    {
        return $this->getHelperSet()->get('service_loader');
    }
}
