<?php

namespace plugse\server\core\app\validation;

use Exception;

class Validation
{
    private Exception $exception;
    private bool $condition;

    public function __construct(
        bool $condition,
        ValidationException $exception
    ) {
        $this->condition = $condition;
        $this->exception = $exception;
    }

    public function validate(): void
    {
        if (!$this->condition) {
            throw $this->exception;
        }
    }
}
