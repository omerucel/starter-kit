<?php

namespace Application\Web\Site;

class InternalError extends BaseController
{
    public function get()
    {
        return $this->render('site/500.twig', array(), 500);
    }
}
