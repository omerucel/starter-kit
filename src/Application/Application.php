<?php

namespace Application;

use Application\Resource\ConfigResource;

class Application
{
    use ConfigResource;

    /**
     * @param Router $router
     */
    public function serve(Router $router)
    {
        $route = $router->getCurrentRoute();
        if ($route == null) {
            $controllerClass = $this->getConfigs()['404Controller'];
            $module = $this->discoverModule($controllerClass);
            $module->dispatch($controllerClass);
        } else {
            $controllerClass = $route->getControllerClass();
            $module = $this->discoverModule($controllerClass);
            $module->dispatch($controllerClass, $route->getRequestMethod(), $route->getParams());
        }
    }

    /**
     * @param  string $controllerClass
     * @return ModuleAbstract
     */
    public function discoverModule($controllerClass)
    {
        $packages = explode('\\', $controllerClass);
        array_pop($packages);
        $moduleClass = implode('\\', $packages);
        $module = new $moduleClass($this);
        return $module;
    }
}
