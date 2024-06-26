<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) nebula <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Nebula\NebulaResponse\Kernel\Events;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ServerGuardResponseCreated.
 *
 * @author nebula <email1946367301@163.com>
 */
class ServerGuardResponseCreated
{
    /**
     * @var Response
     */
    public $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}
