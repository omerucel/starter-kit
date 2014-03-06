<?php

namespace MiniFrame;

/**
 * Bu yardımcı ayar sınıfı, ayar dosyasındaki dizi üzerinden veri alımı ya da veri kaydetme gibi işlemlerde
 * kolaylık sağlar.
 */
class Config
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = json_decode(json_encode($data));
    }

    /**
     * @param $key
     * @param array $default
     * @return array
     */
    public function getArray($key, $default = array())
    {
        return $this->objectToArray($this->get($key, $default));
    }

    /**
     * @param $d
     * @return array
     */
    public function objectToArray($d)
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(array($this, 'objectToArray'), $d); // recursive
        } else {
            return $d;
        }
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = &$this->data;
        foreach (explode('.', $key) as $step) {
            $value = &$value->{$step};
        }

        if ($value === null) {
            return $default;
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $oldValue = &$this->data;
        foreach (explode('.', $key) as $step) {
            $oldValue = &$oldValue->{$step};
        }
        $oldValue = $value;
    }
}
