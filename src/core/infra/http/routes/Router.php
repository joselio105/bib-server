<?php

namespace plugse\server\core\infra\http\routes;

use plugse\server\core\infra\http\Request;
use plugse\server\core\errors\RouteNotFoundError;
use plugse\server\core\errors\RouteInconcistenceError;

class Router
{
    const PATTERN_ALPHA = '[0-9a-z\-]+';
    const PATTERN_ATTR = '\:[0-9a-z\-]+';
    private array $routes;
    private Route $validRoute;
    private Request $request;

    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->setRoutes($routes);
    }

    public function getRoute(): Route
    {
        $validRoutes = array_filter($this->routes, function($route){
            return $route->match($this->request);
        });
        
        if(count($validRoutes)>1){
            throw new RouteInconcistenceError;
        }

        if(count($validRoutes)===0){
            throw new RouteNotFoundError;
        }
        
        $keys = array_keys($validRoutes);
        $validKey = $keys[0];

        return $validRoutes[$validKey];
    }

    public function getParams(Route $route): array
    {
        $request = explode('/', $this->request->uri);
        $route = explode('/', $route->endpoint);
        $keys = str_replace(':', '', array_diff($route, $request));
        $values = array_diff($request, $route);
        
        return array_combine($keys, $values);
    }
    
    private function setRoutes(array $routes)
    {
        $this->routes = [];

        foreach($routes as $route){
            if(get_class($route)===GroupedRoutes::class){
                array_push($this->routes, ...$route->get());
            }else{
                array_push($this->routes, $route);
            }
        }        
    }
}

