<?php

namespace Application\Web;

use MiniFrame\WebApplication\Controller;

abstract class BaseForm
{
    /**
     * @var \MiniFrame\WebApplication\Controller
     */
    protected $controller;

    /**
     * @var array
     */
    public $messages;

    /**
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->messages = array();
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setMessage($key, $value)
    {
        $this->messages[$key] = $value;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return !empty($this->messages);
    }

    /**
     * @return bool
     */
    final public function isValid()
    {
        $this->validateFields();
        return !$this->hasMessage();
    }

    abstract public function loadParamsFromRequest();

    /**
     * @return bool
     */
    abstract protected function validateFields();
}
