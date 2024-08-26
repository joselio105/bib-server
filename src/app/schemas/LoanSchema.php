<?php

namespace plugse\server\app\schemas;

use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\core\app\validation\validations\MustBeInt;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\core\app\validation\validations\MustBeDatetime;

class LoanSchema extends ValidationSchema
{
    public function getSchema(array $attributes): array
    {
        return [
            'copyId' => [
                IsRequired::make($attributes, 'copyId'),
                MustBeInt::make($attributes, 'copyId'),
            ],
            'userId' => [
                IsRequired::make($attributes, 'userId'),
                MustBeInt::make($attributes, 'userId'),
            ],
            'loannedAt' => [
                IsRequired::make($attributes, 'loannedAt'),
                MustBeDatetime::make($attributes, 'loannedAt'),
            ],
            'returnAt' => [
                MustBeDatetime::make($attributes, 'returnAt'),
            ],
            'returnedAt' => [
                MustBeDatetime::make($attributes, 'returnedAt'),
            ],
        ];
    }
}
