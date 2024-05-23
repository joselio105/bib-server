<?php

namespace plugse\server\infra\http\middlewares;

use plugse\server\core\helpers\Crypto;
use plugse\server\core\infra\http\Request;
use plugse\server\core\errors\TokenExpiredError;
use plugse\server\core\errors\PermitionDeniedError;
use plugse\server\core\errors\PermitionIncorrectError;
use plugse\server\core\infra\http\middlewares\Middleware;

class AuthMiddleware implements Middleware
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run(): void
    {
        if (!key_exists('Authorization', $this->request->header)) {
            throw new PermitionDeniedError($this->request->uri, $this->request->httpMethod);
        }

        if (!preg_match('/Bearer\s(\S+)/', $this->request->header['Authorization'], $matches)) {
            throw new PermitionIncorrectError();
        }

        $tokenDecoded = Crypto::DecodeToken($matches[1]);

        if ($tokenDecoded['exp'] <= Crypto::getTimestamp()) {
            throw new TokenExpiredError();
        }
    }
}
