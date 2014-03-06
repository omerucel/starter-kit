<?php

namespace MiniFrame;

abstract class BaseService
{
    /**
     * @var BaseApplication
     */
    protected $application;

    /**
     * @param BaseApplication $application
     */
    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    /**
     * @return BaseApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Ayarlara erişmek için yardımcı metod.
     *
     * @return Config
     */
    public function getConfigs()
    {
        return $this->getApplication()->getConfigs();
    }

    public function init()
    {

    }
}
