<?php

namespace Application\Web\Admin;

class Dashboard extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.dashboard');
        return $this->render('admin/dashboard.twig');
    }
}
