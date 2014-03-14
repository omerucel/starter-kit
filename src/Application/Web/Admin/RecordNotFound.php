<?php

namespace Application\Web\Admin;

class RecordNotFound extends BaseController
{
    public function get()
    {
        return $this->render('admin/404.twig', array(), 404);
    }
}
