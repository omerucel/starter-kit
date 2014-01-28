<?php

namespace Application\Module\Site;

use Application\Module\BaseController;
use Application\Module\Site;

abstract class BaseSiteController extends BaseController
{
    /**
     * @var Site
     */
    protected $module;

    /**
     * @param Site $module
     */
    public function __construct(Site $module)
    {
        $this->module = $module;
    }

    /**
     * @return Site
     */
    public function getModule()
    {
        return $this->module;
    }
}
