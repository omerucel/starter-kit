<?php

namespace Application\Resource;

class ResourceMemory
{
    /**
     * @var array
     */
    protected static $data = array();

    public static function flush()
    {
        static::$data = array();
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        static::$data[$key] = $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (static::hasKey($key)) {
            return static::$data[$key];
        }

        return $default;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function hasKey($key)
    {
        return array_key_exists($key, static::$data);
    }
}
