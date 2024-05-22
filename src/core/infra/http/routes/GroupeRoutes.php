<?php

namespace plugse\server\core\infra\http\routes;

use Closure;

class GroupeRoutes
{
    private array $routes;
    private string $controller;
    private string $prefix;
    private array $middlewares;

    public function __construct()
    {
        $this->routes = [];
        $this->middlewares = [];
    }

    public function setPrefix(string $prefix): GroupeRoutes
    {
        $this->prefix = trim(strtoupper($prefix), '/');
        
        return $this;
    }

    public function setController(string $controller): GroupeRoutes
    {
        $this->controller = $controller;

        return $this;
    }

    public function setMiddleware(string $midleware): GroupeRoutes
    {
        array_push($this->middlewares, $midleware);

        return $this;
    }

    public function addRoute(string $endpoint, string $httpMethod, string $action, array $middlewares = []): GroupeRoutes
    {
        $endpoint = trim(strtolower($endpoint), '/');
        $middlewares = empty($middlewares) ? $this->middlewares : $middlewares;
        $route = new Route("{$this->prefix}/{$endpoint}", $httpMethod, $this->controller, $action, $middlewares);
        array_push($this->routes, $route);

        return $this;
    }

    public function get(): array
    {
        return $this->routes;
    }
}
