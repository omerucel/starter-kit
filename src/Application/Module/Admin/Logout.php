<?php

namespace Application\Module\Admin;

class Logout extends BaseAdminController
{
    public function get()
    {
        $this->getSession()->remove('is_admin_logged_in');
        $this->getSession()->remove('admin');
        return $this->redirect('/admin');
    }
}
