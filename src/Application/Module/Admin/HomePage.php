<?php

namespace Application\Module\Admin;

class HomePage extends BaseAdminController
{
    public function get()
    {
        return $this->render('admin/homepage.twig');
    }
}
