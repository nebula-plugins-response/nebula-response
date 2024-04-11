<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) nebula <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Nebula\NebulaResponse\Kernel;

use GuzzleHttp\Client;
use Monolog\Logger;
use Nebula\NebulaResponse\Kernel\Cache\Cache;
use Nebula\NebulaResponse\Kernel\Providers\CacheServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\EventDispatcherServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\ExtensionServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\HttpClientServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\LogServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\RequestServiceProvider;
use Nebula\NebulaResponse\Kernel\Providers\ConfigServiceProvider;
use Nebula\NebulaResponse\Kernel\Traits\WithAggregator;
use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract Class ServiceContainer.
 *
 * @author nebula <email1946367301@163.com>
 *
 * @property Config $config
 * @property Request $request
 * @property Client $http_client
 * @property Logger $logger
 * @property Cache $cache
 * @property EventDispatcher $events
 */
abstract class ServiceContainer extends Container
{
    use WithAggregator;

    protected $base;
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * Constructor.
     *
     * @param array $config
     * @param array $prepends
     * @param string|null $id
     */
    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        $this->userConfig = $config;

        parent::__construct($prepends);

        $this->registerProviders($this->getProviders());

        $this->id = $id;

        $this->aggregate();

        $this->events->dispatch(new Events\ApplicationInitialized($this));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id ?? $this->id = md5(json_encode($this->userConfig));
    }

    /**
     * @return array
     */
    public function getConfig()
    {
            $base = [
                'http' => [
                    'timeout' => 30.0,
                    'base_uri' => 'https://open.keloop.cn/',
                ],
            ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class,
            CacheServiceProvider::class,
            LogServiceProvider::class,
            RequestServiceProvider::class,
            HttpClientServiceProvider::class,
            ExtensionServiceProvider::class,
            EventDispatcherServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @param string $id
     * @param mixed $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        if ($this->shouldDelegate($id)) {
            return $this->delegateTo($id);
        }

        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
