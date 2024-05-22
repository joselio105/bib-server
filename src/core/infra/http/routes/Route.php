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
    public array $middwares;

    public function __construct(
        string $endpoint, 
        string $httpMehod, 
        string $controller, 
        string $action, 
        array $middwares=[]
    )
    {
        $this->endpoint = $endpoint = trim(strtolower($endpoint), '/');;
        $this->httpMethod = strtoupper($httpMehod);
        $this->controller = $controller;
        $this->action = strtolower($action);
        $this->middwares = $middwares;
        
    }

    public function match(string $requestUri, string $requestHttpMethod='GET'): bool
    {
        $routeSlashed = str_replace('/', '\/', $this->endpoint);
        $pattern = preg_replace("/".self::PATTERN_ATTR."/", "(".self::PATTERN_ALPHA.")", $routeSlashed);
        
        $matches = preg_match("/^{$pattern}$/", $requestUri)===1;
        $matchMehod = $this->httpMethod === $requestHttpMethod;

        return  $matches and $matchMehod;
    }
}
