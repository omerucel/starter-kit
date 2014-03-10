<?php

namespace Application\Web;

use MiniFrame\Extra\Service\AuthService\Exception\AccessDeniedException;
use MiniFrame\WebApplication\Module;

class Admin extends Module
{
    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function handleException(\Exception $exception)
    {
        if ($exception instanceof AccessDeniedException) {
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
