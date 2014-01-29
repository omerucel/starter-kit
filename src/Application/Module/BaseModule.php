<?php

namespace Application\Module;

use Application\Application;
use Application\ModuleAbstract;
use Application\Resource\ConfigResource;
use Application\Resource\HttpResource;
use Application\Resource\MonologResource;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseModule extends ModuleAbstract
{
    use ConfigResource;
    use HttpResource;
    use MonologResource;

    protected static $isFirstDispatch = false;

    /**
     * @return mixed
     */
    abstract public function internalServerError();

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->setErrorHandler();
    }

    /**
     * @param $controllerClass
     * @param string $requestMethod
     * @param array $params
     * @return mixed|void
     */
    public function dispatch($controllerClass, $requestMethod = 'get', array $params = array())
    {
        if (!static::$isFirstDispatch) {
            $response = $this->boot();
            if ($response instanceof Response) {
                $response->send();
                return;
            }
        }

        /**
         * @var BaseController $controller
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
                $response = $this->getResponse();
            }
        }

        $response->send();
    }

    /**
     * TODO : Bu metod modül bootstrap sınıfı oluşturulup içine eklenebilir.
     *
     * @return null
     */
    public function boot()
    {
        static::$isFirstDispatch = true;
        $configs = $this->getConfigs();

        AnnotationRegistry::registerAutoloadNamespaces(
            array(
                'Symfony\Component\Validator\Constraints' => $configs['base_path'] . '/vendor/symfony/validator',
                'Application' => $configs['base_path'] . '/src/'
            )
        );

        /**
         * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface $serviceContainer
         */
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass($configs['pdo']['dbname'], 'mysql');
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration(array(
            'dsn'      => $configs['pdo']['dsn'],
            'user'     => $configs['pdo']['username'],
            'password' => $configs['pdo']['password'],
        ));
        $serviceContainer->setConnectionManager($configs['pdo']['dbname'], $manager);

        return null;
    }

    public function setErrorHandler()
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

        $this->getMonolog()->error($message);
        $this->internalServerError();
    }

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

            $this->getMonolog()->error($message);
            $this->internalServerError();
        }
    }
}
