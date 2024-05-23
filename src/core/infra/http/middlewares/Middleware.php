<?php

namespace plugse\server\core\infra\http\midlewares;

use plugse\server\core\infra\http\Request;

interface Middleware
{
    public function __construct(Request $request);
    public function run(): void;
}
