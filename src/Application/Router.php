<?php

namespace Application;

class Router
{
    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var Route
     */
    protected $currentRoute;

    /**
     * @var array
     */
    protected $tokens = array(
        ':string' => '([a-zA-Z]+)',
        ':number' => '([0-9]+)',
        ':alpha'  => '([a-zA-Z0-9-_]+)',
        ':slug' => '([\w\-]+)'
    );

    /**
     * @param array $routes
     * @param array $tokens
     */
    public function __construct(array $routes = array(), array $tokens = array())
    {
        $this->routes = $routes;
        $this->tokens = array_merge($this->tokens, $tokens);
    }

    /**
     * @return Route
     */
    public function getCurrentRoute()
    {
        if ($this->currentRoute == null) {
            $this->currentRoute = $this->createRoute();
        }

        return $this->currentRoute;
    }

    /**
     * @return Route
     */
    public function createRoute()
    {
        $pathInfo = $this->getPathInfo();
        $requestMethod = $this->getRequestMethod();
        $route = null;

        if (isset($this->routes[$pathInfo])) {
            $route = new Route($pathInfo, $this->routes[$pathInfo], $requestMethod);
        } else {
            foreach ($this->routes as $pattern => $className) {
                $regex = '#^/?' . strtr($pattern, $this->tokens) . '/?$#';

                if (preg_match($regex, $pathInfo, $matches)) {
                    unset($matches[0]);
                    $route = new Route(
                        $pattern,
                        $className,
                        $requestMethod,
                        $matches
                    );
                    break;
                }
            }
        }

        return $route;
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        $pathInfo = '/';
        if (!empty($_SERVER['PATH_INFO'])) {
            $pathInfo = $_SERVER['PATH_INFO'];
        } elseif (!empty($_SERVER['ORIG_PATH_INFO'])
            && $_SERVER['ORIG_PATH_INFO'] !== '/index.php'
        ) {
            $pathInfo = $_SERVER['ORIG_PATH_INFO'];
        } else {
            if (!empty($_SERVER['REQUEST_URI'])) {
                if (strpos($_SERVER['REQUEST_URI'], '?') > 0) {
                    $pathInfo = strstr($_SERVER['REQUEST_URI'], '?', true);
                } else {
                    $pathInfo = $_SERVER['REQUEST_URI'];
                }
            }
        }

        return $pathInfo;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        if ($this->isXhrRequest()) {
            $requestMethod.= 'Xhr';
        }

        return $requestMethod;
    }

    /**
     * @return boolean
     */
    public function isXhrRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}
