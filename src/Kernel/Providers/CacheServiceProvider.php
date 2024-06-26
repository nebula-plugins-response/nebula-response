<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) nebula <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Nebula\NebulaResponse\Kernel\Providers;

use Nebula\NebulaResponse\Kernel\Cache\Cache;
use Nebula\NebulaResponse\Kernel\Log\LogManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class CacheServiceProvider.
 *
 * @author nebula <email1946367301@163.com>
 */
class CacheServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        !isset($pimple['cache']) && $pimple['cache'] = function ($app) {
            $config = $this->formatCacheConfig($app);
            if (!empty($config)) {
                $app->rebind('config', $app['config']->merge($config));
            }
            return new Cache($app);
        };
    }

    public function formatCacheConfig($app)
    {
        if (!empty($app['config']->get('cache.type'))) {
            return $app['config']->get('cache');
        }
        if (empty($app['config']->get('cache'))) {
            return [
                'cache' => [
                    // 驱动方式
                    'type'   => 'FILE',
                    // 缓存保存目录
                    'path'   => \sys_get_temp_dir() . '/cache',
                    // 缓存前缀
                    'prefix' => '',
                    // 缓存有效期 0表示永久缓存
                    'expire' => 0,
                ],
            ];
        }
        return [
            'cache' => [
                // 驱动方式
                'type'   => $app['config']->get('cache.type') ?? 'FILE',
                // 缓存保存目录
                'path'   => $app['config']->get('cache.path') ?? \sys_get_temp_dir() . '/cache',
                // 缓存前缀
                'prefix' => $app['config']->get('cache.prefix') ?? '',
                // 缓存有效期 0表示永久缓存
                'expire' => $app['config']->get('cache.expire') ?? 0,
            ],
        ];
    }
}
