<?php

namespace plugse\server\app\schemas;

use plugse\server\infra\database\mysql\UserModel;
use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\core\app\validation\validations\IsUnique;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\core\app\validation\validations\MustBeBool;
use plugse\server\core\app\validation\validations\MustBeEmail;
use plugse\server\core\app\validation\validations\MustBePhone;
use plugse\server\core\app\validation\validations\MustBeString;
use plugse\server\core\app\validation\validations\MustHaveLenght;
use plugse\server\core\app\validation\validations\MustHaveNumbers;
use plugse\server\core\app\validation\validations\MustHaveSpecialChars;
use plugse\server\core\app\validation\validations\MustHaveLowerCaseChars;
use plugse\server\core\app\validation\validations\MustHaveUpperCaseChars;

class UserSchema extends ValidationSchema
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
                IsUnique::make($attributes, 'email', new UserModel()),
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
            'password' => [
                MustHaveLowerCaseChars::make($attributes, 'password'),
                MustHaveUpperCaseChars::make($attributes, 'password'),
                MustHaveNumbers::make($attributes, 'password'),
                MustHaveSpecialChars::make($attributes, 'password'),
                MustHaveLenght::make(
                    $attributes,
                    'password',
                    8,
                    MustHaveLenght::LENGTH_GREATHER_EQUALS
                ),
            ],
        ];
    }
}
