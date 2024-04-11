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

use Nebula\NebulaResponse\Kernel\Log\LogManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class LoggingServiceProvider.
 *
 * @author nebula <email1946367301@163.com>
 */
class LogServiceProvider implements ServiceProviderInterface
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
        !isset($pimple['log']) && $pimple['log'] = function ($app) {
            $config = $this->formatLogConfig($app);
            if (!empty($config)) {
                $app->rebind('config', $app['config']->merge($config));
            }
            return new LogManager($app);
        };

        !isset($pimple['logger']) && $pimple['logger'] = $pimple['log'];
    }

    public function formatLogConfig($app)
    {
        if (!empty($app['config']->get('log.channels'))) {
            return $app['config']->get('log');
        }

        if (empty($app['config']->get('log'))) {
            return [
                'log' => [
                    'default'  => 'null',
                    'channels' => [
                        'null' => [
                            'driver' => 'null',
                        ],
                    ],
                ],
            ];
        }

        return [
            'log' => [
                'default'  => 'single',
                'channels' => [
                    'single' => [
                        'driver' => 'single',
                        'path'   => $app['config']->get('log.file') ?: \sys_get_temp_dir() . '/logs/nebulaResponse.log',
                        'level'  => $app['config']->get('log.level', 'debug'),
                    ],
                ],
            ],
        ];
    }
}
