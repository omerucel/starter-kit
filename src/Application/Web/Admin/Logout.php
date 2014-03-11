<?php

namespace Application\Web\Admin;

class Logout extends BaseController
{
    public function get()
    {
        $this->getAuthService()->checkPermission('admin.logout');

        $this->getAuthService()->newUserActivity('admin.logout');
        $this->getAuthService()->logout();
        return $this->redirect('/admin');
    }
}
