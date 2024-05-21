<?php

namespace plugse\server\core\infra\http;

class Response
{
    private mixed $value;

    public function __construct(mixed $value, int $statusCode=200)
    {
        $this->value = $value;
        http_response_code($statusCode);
    }

    public function get()
    {
        return is_string($this->value)? ['message'=>$this->value]: $this->value;
    }
}
