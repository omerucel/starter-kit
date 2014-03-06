<?php

namespace Application\Web\Admin;

class InternalError extends BaseController
{
    public function get()
    {
        return $this->render('admin/500.twig', array(), 500);
    }
}
