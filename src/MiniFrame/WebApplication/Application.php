<?php

namespace MiniFrame\WebApplication;

use MiniFrame\BaseApplication;
use MiniFrame\Extra\Service\MonologService;

class Application extends BaseApplication
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $modules;

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        parent::__construct($configs);
        $this->modules = array();
        $this->routes = $this->getConfigs()->getArray('web_application.router.routes');
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
            $module->dispatch($controllerClass);
        } else {
            $controllerClass = $route->getControllerClass();
            $module = $this->discoverModule($controllerClass);
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
        $monologService = $this->getService('monolog');
        $monologService->getDefaultLogger()->error($moduleClass . ' not found.');
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if ($this->router == null) {
            $routeTokens = $this->getConfigs()->getArray('web_application.router.tokens');
            $this->router = new Router($this->routes, $routeTokens);
        }

        return $this->router;
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
            $this->modules[$moduleClass] = new $moduleClass($this);
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
}
