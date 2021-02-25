<?php
namespace App_Settings;

use \Exception as Exception;

class APP_Config
{

    private static $TestMsg = 'Conf Here';

    // private static $JwtIssuer = 'b-Server';
    // private static $JwtAudience = 'client';
    // private static $JwtNbf = 0;
    // private static $JwtExp = 3600;
    // private static $JwtSecretCode = 'QaLmW2N7';

    public static function __callStatic($name, $arguemnts)
    {
        $class = get_class();

        if (strpos($name, 'get') === 0) {
            $key = preg_replace('/^get/', '', $name);

            if (property_exists($class, $key)) {
                $value = self::$$key;

                if (strpos($key, 'Log') === 0) {
                    return $value;
                }

                if (is_string($value) && !$value) {
                    throw new Exception("`{$key}`不能为空，请确保 SDK 配置已正确初始化", 1);
                }

                return $value;
            }
        }

        if (strpos($name, 'set') === 0) {
            $key   = preg_replace('/^set/', '', $name);
            $value = isset($arguemnts[0]) ? $arguemnts[0] : null;

            if (property_exists($class, $key)) {
                if (gettype($value) === gettype(self::$$key)) {
                    self::$$key = $value;
                } else {
                    throw new Exception("Call to method {$class}::{$name}() with invalid arguements", 1);
                }
                return;
            }
        }

        throw new Exception("Call to undefined method {$class}::{$name}()", 1);
    }

    public static function setup($config = null)
    {
        if (!is_array($config)) {
            throw new Exception(Constants::E_INIT_LOST_CONFIG);
        }

        $class = get_class();

        foreach ($config as $key => $value) {
            $key = ucfirst($key);
            if (property_exists($class, $key)) {
                if (gettype($value) === gettype(self::$$key)) {
                    if (gettype($value) === 'array') {
                        self::$$key = array_merge(self::$$key, $value);
                    } else {
                        self::$$key = $value;
                    }
                } else {
                    throw new Exception(Constants::E_INIT_CONFIG_TYPE . ': ' . $key);
                }
            }
        }
    }
}
