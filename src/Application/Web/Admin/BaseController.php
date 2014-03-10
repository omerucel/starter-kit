<?php

namespace Application\Web\Admin;

use Application\Web\Admin;
use MiniFrame\WebApplication\Controller;

abstract class BaseController extends Controller
{
    public function preDispatch()
    {
        if ($this->getAuthService()->getCurrentUser() == null && !$this instanceof Login) {
            return $this->redirect('/admin/login');
        }

        if ($this->getAuthService()->getCurrentUser() != null && $this instanceof Login) {
            return $this->redirect('/admin');
        }

        return parent::preDispatch();
    }
}
