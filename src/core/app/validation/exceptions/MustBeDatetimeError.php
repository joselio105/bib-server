<?php

namespace plugse\server\core\app\validation\exceptions;

use Exception;

class MustBeDatetimeError extends Exception
{
    public function __construct(string $name)
    {
        http_response_code(406);
        parent::__construct("A variável {$name} deve ser do tipo Datetime");
    }
}
