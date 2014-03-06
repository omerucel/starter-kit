<?php

namespace Application\Web\Admin;

class Logout extends BaseController
{
    public function get()
    {
        $this->newUserActivity('admin.logout');
        $this->getSession()->remove('admin_user_id');
        return $this->redirect('/admin');
    }
}
