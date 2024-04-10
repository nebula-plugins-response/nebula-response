<?php

namespace Nebula\NebulaResponse;

use Nebula\NebulaResponse\Kernel\ServiceContainer;

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Class Factory.
 */
abstract class AbstractFactory
{

    /**
     * @param string $name
     * @param array $config
     *
     * @return ServiceContainer
     */
    public static function make(string $name, array $config): ServiceContainer
    {
        $called_class               = get_called_class();
        $namespaceAarry             = explode('\\', $called_class);
        $count                      = count($namespaceAarry);
        $namespace                  = Kernel\Support\Str::studly($name);
        $namespaceAarry[$count - 1] = $namespace;
        $namespaceAarry[$count]     = 'Application';
        $application                = "\\" . implode('\\', $namespaceAarry);
        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return ServiceContainer
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
