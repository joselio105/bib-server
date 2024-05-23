<?php

namespace plugse\test\mocks\middlewares;

use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\midlewares\Middleware;

class TestMiddleware implements Middleware
{
    public function __construct(Request $request)
    {
        
    }

    public function run(): void
    {
        
    }
}
