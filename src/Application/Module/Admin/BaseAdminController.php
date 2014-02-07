<?php

namespace Application\Module\Admin;

use Application\Module\BaseController;
use Application\Module\Admin;
use Application\Resource\PdoResource;
use Application\Resource\PropelResource;
use Application\Resource\SessionResource;

abstract class BaseAdminController extends BaseController
{
    use SessionResource;
    use PdoResource;

    /**
     * @var Admin
     */
    protected $module;

    /**
     * @param Admin $module
     */
    public function __construct(Admin $module)
    {
        $this->module = $module;
    }

    /**
     * @return Admin
     */
    public function getModule()
    {
        return $this->module;
    }

    public function preDispatch()
    {
        if ($this->getSession()->has('is_admin_logged_in')) {
            return null;
        }

        if (!$this instanceof Login) {
            return $this->redirect('/admin/login');
        }
    }
}
