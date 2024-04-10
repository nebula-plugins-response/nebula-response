<?php

namespace Nebula\NebulaResponse;

use Nebula\NebulaResponse\Kernel\ServiceContainer;

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Class Factory.
 */
abstract class AbstractFactory
{

    protected static $application = "\\Nebula\\NebulaResponse\\{namespace}\\Application";

    /**
     * @param string $name
     * @param array $config
     *
     * @return ServiceContainer
     */
    public static function make(string $name, array $config): ServiceContainer
    {
        $namespace   = Kernel\Support\Str::studly($name);
        $application = str_replace(['{namespace}'], [$namespace], self::$application);
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
