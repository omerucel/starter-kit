<?php

namespace Application\Web\Admin\QueryString;

abstract class BaseQS
{
    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param array $except
     * @param array $extra
     * @return string
     */
    public function createQueryString(array $except = array(), array $extra = array())
    {
        $params = array();
        foreach ($this->params as $key => $value) {
            if (!in_array($key, $except)) {
                $params[$key] = $value;
            }
        }

        $params = array_merge($params, $extra);
        return http_build_query($params);
    }
}
