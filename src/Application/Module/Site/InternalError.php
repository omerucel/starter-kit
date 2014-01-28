<?php

namespace Application\Module\Site;

use Application\Module\BaseController;

class InternalError extends BaseController
{
    public function get()
    {
        return $this->render('site/500.twig', array(), 500);
    }
}
