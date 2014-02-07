<?php

namespace Application\Module\Admin;

use Application\Module\BaseController;

class InternalError extends BaseController
{
    public function get()
    {
        return $this->render('admin/500.twig', array(), 500);
    }
}
