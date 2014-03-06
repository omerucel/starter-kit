<?php

namespace Application\Web\Admin;

class Dashboard extends BaseController
{
    public function get()
    {
        return $this->render('admin/dashboard.twig');
    }
}
