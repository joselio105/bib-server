<?php

namespace plugse\server\core\infra\http;

use plugse\server\core\infra\http\routes\Route;

class Request
{
    public array $params;
    public array $body;
    public string $uri;
    public Route $route;
}
