<?php

namespace plugse\server\core\infra\http\middlewares;

use plugse\server\core\infra\http\Request;

interface Middleware
{
    public function __construct(Request $request);
    public function run(): void;
}
