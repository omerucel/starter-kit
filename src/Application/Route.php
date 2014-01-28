<?php

namespace Application;

class Route
{
    /**
     * @var string
     */
    protected $pattern = '';

    /**
     * @var string
     */
    protected $className = '';

    /**
     * @var string
     */
    protected $requestMethod = '';

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param $pattern
     * @param $className
     * @param string $requestMethod
     * @param array $params
     */
    public function __construct($pattern, $className, $requestMethod = '', array $params = array())
    {
        $this->pattern = $pattern;
        $this->className = $className;
        $this->requestMethod = $requestMethod;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
