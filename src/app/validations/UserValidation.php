<?php

use plugse\server\core\app\validation\ValidationTypes;

return [
    'name' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_HAVE_LENGTH_GREATHER_THAN, 3
    ],
    'email'=>[
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_BE_EMAIL
    ],
    'phone'=>[
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_BE_PHONE
        ]
];