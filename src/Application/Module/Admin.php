<?php

namespace Application\Module;

class Admin extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $this->dispatch('Application\Module\Admin\InternalError');
        exit;
    }

    public function pageNotFound()
    {
        $this->dispatch('Application\Module\Admin\NotFound');
        exit;
    }
}
