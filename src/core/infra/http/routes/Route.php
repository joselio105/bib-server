<?php

namespace plugse\server\core\infra\http\routes;

class Route
{
    const PATTERN_ALPHA = '[0-9a-z\-]+';
    const PATTERN_ATTR = '\:([0-9a-z\-]+)';
    public string $endpoint;
    public string $httpMethod;
    public string $controller;
    public string $action;
    public array $midwares;

    public function __construct(string $endpoint, string $httpMehod, string $controller, string $action, array $midwares=[])
    {
        $this->endpoint = $endpoint;
        $this->httpMethod = $httpMehod;
        $this->controller = $controller;
        $this->action = $action;
        $this->midwares = $midwares;
        
    }

    public function match(string $requestUri): bool
    {
        $routeSlashed = str_replace('/', '\/', $this->endpoint);
        $pattern = preg_replace("/".self::PATTERN_ATTR."/", "(".self::PATTERN_ALPHA.")", $routeSlashed);
        
        return preg_match("/^{$pattern}$/", $requestUri)===1;
    }
}
