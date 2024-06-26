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

use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponseCreated.
 *
 * @author nebula <email1946367301@163.com>
 */
class HttpResponseCreated
{
    /**
     * @var ResponseInterface
     */
    public $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
