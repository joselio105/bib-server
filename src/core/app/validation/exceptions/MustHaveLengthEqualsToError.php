<?php

namespace plugse\server\core\app\validation\exceptions;

use plugse\server\core\app\validation\ValidationException;
use plugse\server\core\app\validation\validations\MustHaveLenght;

class MustHaveLengthEqualsToError extends ValidationException
{
    public function __construct(string $name, int $length, string $condition)
    {
        $conditions = [
            MustHaveLenght::LENGTH_EQUALS => 'deve ter',
            MustHaveLenght::LENGTH_GREATHER => 'deve ter mais de',
            MustHaveLenght::LENGTH_SMALLER => 'deve ter menos de',
            MustHaveLenght::LENGTH_GREATHER_EQUALS => 'deve ter igual ou mais de',
            MustHaveLenght::LENGTH_SMALLER_EQUALS => 'deve ter igual ou menos de',
        ];
        parent::__construct("A variável {$name} {$conditions[$condition]} {$length} caracteres", 411);
    }
}
