<?php

namespace plugse\server\app\schemas;

use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\core\app\validation\validations\IsRequired;

class CopySchema extends ValidationSchema
{
    public function getSchema(array $attributes): array
    {
        return [
            'publicationId' => [
                IsRequired::make($attributes, 'publicationId'),
            ],
        ];
    }
}
