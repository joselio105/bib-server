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
    private string $requestUri;
    private string $requestHttpMethod;
    private array $requestBody;

    public function __construct(string $requestUri, string $requestHttpMethod, array $routes, array $requestBody=null)
    {
        $this->requestUri = $requestUri;
        $this->requestHttpMethod = strtoupper($requestHttpMethod);
        $this->setRoutes($routes);
        $this->requestBody = $requestBody ?? $_POST;
    }
    
    public function getRequest()
    {
        $this->setValidRoute();
        $request = new Request;
        $request->route = $this->validRoute;
        $request->uri = $this->requestUri;
        $request->params = $this->getParams();
        $request->body = $this->requestBody;
        
        return $request;
    }
    
    private function setRoutes(array $routes)
    {
        $this->routes = [];

        foreach($routes as $route){
            if(get_class($route)===GroupeRoutes::class){
                array_push($this->routes, ...$route->get());
            }else{
                array_push($this->routes, $route);
            }
        }        
    }

    private function setValidRoute(): void
    {
        $validRoutes = array_filter($this->routes, function($route){
            return $route->match($this->requestUri, $this->requestHttpMethod);
        });
        
        if(count($validRoutes)>1){
            throw new RouteInconcistenceError;
        }

        if(count($validRoutes)===0){
            throw new RouteNotFoundError;
        }
        
        $keys = array_keys($validRoutes);
        $validKey = $keys[0];
        $this->validRoute = $validRoutes[$validKey];
    }

    private function getParams(): array
    {
        $request = explode('/', $this->requestUri);
        $route = explode('/', $this->validRoute->endpoint);
        $keys = str_replace(':', '', array_diff($route, $request));
        $values = array_diff($request, $route);
        
        return array_combine($keys, $values);
    }
}

