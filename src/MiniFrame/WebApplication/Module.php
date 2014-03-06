<?php

namespace MiniFrame\WebApplication;

use MiniFrame\BaseApplication;
use MiniFrame\Extra\Service\HttpFoundationService;
use MiniFrame\Extra\Service\MonologService;
use Symfony\Component\HttpFoundation\Response;

abstract class Module
{
    /**
     * @var BaseApplication
     */
    protected $application;

    /**
     * @param BaseApplication $application
     */
    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Modül, Application sınıfında ilk yüklenirken çağrılır.
     */
    public function init()
    {
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return null|Response
     */
    protected function preDispatch()
    {
        return null;
    }

    /**
     * @return null|Response
     */
    protected function postDispatch()
    {
        return null;
    }

    /**
     * @param $controllerClass
     * @param string $requestMethod
     * @param array $params
     * @return mixed|void
     */
    public function dispatch($controllerClass, $requestMethod = 'get', array $params = array())
    {
        /**
         * @var Controller $controller
         */
        $controller = new $controllerClass($this);

        /**
         * @var Response $response
         */
        $response = $controller->preDispatch();
        if (!$response instanceof Response) {
            $response = call_user_func_array(array($controller, $requestMethod), $params);

            $postDispatchResponse = $controller->postDispatch();
            if ($postDispatchResponse instanceof Response) {
                $response = $postDispatchResponse;
            }

            if (!$response instanceof Response) {
                /**
                 * @var HttpFoundationService $serviceHelper
                 */
                $serviceHelper = $this->getApplication()->getService('http_foundation');
                $response = $serviceHelper->getResponse();
            }
        }

        $response->send();
    }


    public function registerErrorHandlers()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));
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
        $message = $exception->getMessage() . ' ' . $exception->getTraceAsString();
        $message = str_replace("\n", '', $message);

        /**
         * @var MonologService $monologService;
         */
        $monologService = $this->getApplication()->getService('monolog');
        $monologService->getDefaultLogger()->error($message);
        $this->handleException($exception);
    }

    /**
     * Ölümcül bir hata oluştuğunda bu metod tetiklenir.
     *
     * @return mixed
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            $errNo = $error["type"];
            $errFile = $error["file"];
            $errLine = $error["line"];
            $errStr = $error["message"];

            $message = $errNo . ' ' . $errStr . ' ' . $errFile . ':' . $errLine;
            $message = str_replace("\n", '', $message);

            /**
             * @var MonologService $monologService;
             */
            $monologService = $this->getApplication()->getService('monolog');
            $monologService->getDefaultLogger()->error($message);
            $this->handleFatalError($errStr, $errNo, $errFile, $errLine);
        }
    }

    /**
     * @param \Exception $exception
     * @return mixed
     */
    abstract public function handleException(\Exception $exception);

    /**
     * @param $errStr
     * @param $errNo
     * @param $errFile
     * @param $errLine
     * @return mixed
     */
    abstract public function handleFatalError($errStr, $errNo, $errFile, $errLine);
}
