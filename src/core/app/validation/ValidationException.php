<?php

namespace plugse\server\core\app\validation;

use Exception;

class ValidationException extends Exception
{
    public function __construct(string $message, int $httpCode = 400)
    {
        http_response_code($httpCode);
        parent::__construct($message);
    }
}
