<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;
use MiniFrame\WebApplication\Router;

class RouterService extends BaseService
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @return Router
     */
    public function getRouter()
    {
        if ($this->router == null) {
            $routes = $this->getConfigs()->getArray('web_application.router.routes');
            $routeTokens = $this->getConfigs()->getArray('web_application.router.tokens');
            $this->router = new Router($routes, $routeTokens);
        }

        return $this->router;
    }
}
