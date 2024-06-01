<?php

namespace plugse\server\core\infra\http;

use plugse\server\core\infra\http\routes\Route;

/**
 * @property string $uri
 * @property string $httpMethod
 * @property array $params
 * @property array $body
 * @property array $header
 */
class Request
{
    private string $uri;
    private string $httpMethod;
    private array $params;
    private array $body;
    private array $header;

    public function __construct()
    {
        $this->uri = key_exists('REQUEST_URI', $_SERVER) ? strtolower(trim($_SERVER['REQUEST_URI'], '/')): '';
        $this->httpMethod = key_exists('REQUEST_METHOD', $_SERVER) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        $this->body = $_POST;
        $this->header = function_exists('apache_request_headers') ? apache_request_headers() : [];
        $this->params = [];
    }

    public function __get($name)
    {
        return $this->$name;   
    }

    public function setUri(string $value): Request
    {
        $this->uri = trim($value, '/');

        return $this;
    }

    public function setHttpMethod(string $value): Request
    {
        $this->httpMethod = strtoupper($value);
        
        return $this;
    }

    public function setParams(array $value): Request
    {
        $this->params = $value;
        
        return $this;
    }

    public function setBody(array $value): Request
    {
        $this->body = $value;
        
        return $this;
    }

    public function setHeader(array $value): Request
    {
        $this->header = $value;
        
        return $this;
    }
}
