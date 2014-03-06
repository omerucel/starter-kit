<?php

namespace MiniFrame\ConsoleApplication;

use Symfony\Component\Console\Helper\Helper;

class ApplicationHelper extends Helper
{
    /**
     * @var \Application\Console\Application
     */
    protected $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param null $name
     * @return \MiniFrame\BaseService
     */
    public function getService($name = null)
    {
        return $this->application->getService($name);
    }

    /**
     * @return \MiniFrame\Config
     */
    public function getConfigs()
    {
        return $this->application->getConfigs();
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
        return 'application';
    }
}
