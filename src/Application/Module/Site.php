<?php

namespace Application\Module;

class Site extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $this->dispatch('Application\Module\Site\InternalError');
        exit;
    }

    public function pageNotFound()
    {
        $this->dispatch('Application\Module\Site\NotFound');
        exit;
    }
}
