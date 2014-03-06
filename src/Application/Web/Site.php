<?php

namespace Application\Web;

use MiniFrame\WebApplication\Module;

class Site extends Module
{
    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function handleException(\Exception $exception)
    {
        $this->dispatch('Application\Web\Site\InternalError');
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
        $this->dispatch('Application\Web\Site\InternalError');
    }
}
