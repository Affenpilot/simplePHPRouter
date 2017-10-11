<?php

namespace Configuration;

/**
 * Class Configuration.
 */
class Configuration
{
    /**
     * @var array
     */
    private static $registry = [];

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$registry[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return bool|mixed
     */
    public static function get($key)
    {
        if(array_key_exists($key, self::$registry)) {
            return self::$registry[$key];
        }

        return false;
    }
}
