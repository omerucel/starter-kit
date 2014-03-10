<?php

namespace MiniFrame\WebApplication;

use MiniFrame\BaseApplication;
use MiniFrame\Extra\Service\MonologService;
use MiniFrame\Extra\Service\RouterService;
use MiniFrame\ServiceLoader;

class Application extends BaseApplication
{
    /**
     * @var array
     */
    protected $modules;

    /**
     * @param ServiceLoader $serviceLoader
     */
    public function __construct(ServiceLoader $serviceLoader)
    {
        parent::__construct($serviceLoader);
        $this->modules = array();
    }

    public function serve()
    {
        /**
         * @var Module $module
         */
        $route = $this->getRouter()->getCurrentRoute();
        if ($route == null) {
            $controllerClass = $this->getConfigs()->get('web_application.default_not_found_controller');
            $module = $this->discoverModule($controllerClass);
            $this->getServiceLoader()->setService('current_module', $module);
            $module->dispatch($controllerClass);
        } else {
            $controllerClass = $route->getControllerClass();
            $module = $this->discoverModule($controllerClass);
            $this->getServiceLoader()->setService('current_module', $module);
            $module->dispatch($controllerClass, $route->getRequestMethod(), $route->getParams());
        }
    }

    /**
     * @param $moduleClass
     */
    public function moduleNotFound($moduleClass)
    {
        /**
         * @var MonologService $monologService
         */
        $monologService = $this->getServiceLoader()->getService('monolog');
        $monologService->getDefaultLogger()->error($moduleClass . ' not found.');
    }

    /**
     * Verilen kontrol sınıfının modülünü bulur.
     *
     * @param  string $controllerClass
     * @return Module
     */
    public function discoverModule($controllerClass)
    {
        $packages = explode('\\', $controllerClass);
        array_pop($packages);
        $moduleClass = implode('\\', $packages);

        // Daha önce yüklenmişse ilgili objeyi dön yoksa oluştur.
        $init = false;
        if (!isset($this->modules[$moduleClass])) {
            $this->modules[$moduleClass] = new $moduleClass($this->getServiceLoader());
            $init = true;
        }

        /**
         * @var Module $module
         */
        $module = $this->modules[$moduleClass];
        $module->registerErrorHandlers();
        if ($init) {
            $module->init();
        }

        return $module;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        /**
         * @var RouterService $routerService
         */
        $routerService = $this->getServiceLoader()->getService('router');
        return $routerService->getRouter();
    }
}
