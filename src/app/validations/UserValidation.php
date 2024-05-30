<?php

use plugse\server\core\app\validation\ValidationTypes;

return [
    'name' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING
    ],
    'email'=>[
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_BE_EMAIL
    ],
    'phone'=>[
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_BE_PHONE
        ]
];