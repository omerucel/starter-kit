<?php

namespace Application\Web\Site;

class HomePage extends BaseController
{
    public function get()
    {
        return $this->render('site/index.twig');
    }
}
