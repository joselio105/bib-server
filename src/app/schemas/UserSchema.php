<?php

use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\core\app\validation\validations\MustBeBool;
use plugse\server\core\app\validation\validations\MustBeEmail;
use plugse\server\core\app\validation\validations\MustBePhone;
use plugse\server\core\app\validation\validations\MustBeString;
use plugse\server\core\app\validation\validations\MustHaveLenght;

class UserSchema implements ValidationSchema
{
    public function getSchema(array $attributes): array
    {
        return [
            'name' => [
                IsRequired::make($attributes, 'name'),
                MustBeString::make($attributes, 'name'),
                MustHaveLenght::make($attributes, 'name', 4, MustHaveLenght::LENGTH_GREATHER_EQUALS),
            ],
            'email' => [
                IsRequired::make($attributes, 'email'),
                MustBeEmail::make($attributes, 'email'),
            ],
            'phone' => [
                IsRequired::make($attributes, 'phone'),
                MustBePhone::make($attributes, 'phone'),
            ],
            'isActive' => [
                IsRequired::make($attributes, 'isActive'),
                MustBeBool::make($attributes, 'isActive'),
            ],
            'isAdmin' => [
                IsRequired::make($attributes, 'isAdmin'),
                MustBeBool::make($attributes, 'isAdmin'),
            ],
        ];
    }
}
