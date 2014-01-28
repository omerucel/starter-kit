<?php

namespace Application\Command;

use Application\Resource\ConfigResource;
use Application\Resource\MonologResource;

abstract class BaseCommand
{
    use ConfigResource;
    use MonologResource;

    /**
     * @return mixed
     */
    abstract protected function start();

    /**
     * Tüm komutlar init ile çalıştırılır. Bu şekilde hata denetimi için gerekli ayarlar yapılmış olur.
     */
    public function init()
    {
        $this->setErrorHandler();
        $this->start();
    }

    public function setErrorHandler()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
    }

    /**
     * Bir hata oluştuğunda bu metod tetiklenir.
     *
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @throws \ErrorException
     */
    public function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
    }

    /**
     * Bir exception fırlatıldığında bu metod tetiklenir.
     *
     * @param \Exception $exception Fırlatılan \Exception sınıfı.
     * @return void
     */
    public function exceptionHandler(\Exception $exception)
    {
        $message = $exception->getMessage() . ' '
            . $exception->getTraceAsString();
        $message = str_replace("\n", '', $message);

        $this->getMonolog()->error($message);
    }
}
