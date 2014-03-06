<?php

namespace Application\Web;

use Application\Web\Admin\Exception\AccessDenied;
use MiniFrame\WebApplication\Module;

class Admin extends Module
{
    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function handleException(\Exception $exception)
    {
        if ($exception instanceof AccessDenied) {
            $this->dispatch('Application\Web\Admin\AccessDenied');
        } else {
            $this->dispatch('Application\Web\Admin\InternalError');
        }
    }

    /**
     * @param $errStr
     * @param $errNo
     * @param $errFile
     * @param $errLine
     * @return mixed
     */
    public function handleFatalError($errStr, $errNo, $errFile, $errLine)
    {
        $this->dispatch('Application\Web\Admin\InternalError');
    }
}
