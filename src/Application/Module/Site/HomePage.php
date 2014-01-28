<?php

namespace Application\Module\Site;

class HomePage extends BaseSiteController
{
    public function get()
    {
        return $this->render('site/homepage.twig');
    }
}
