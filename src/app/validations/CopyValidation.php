<?php

use plugse\server\core\app\validation\ValidationTypes;

return [
    'registrationCode' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_REGISTRATION
    ],
    'publicationId' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_INT
    ],
    'createdAt' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_DATETIME
    ],
    'createdBy' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_INT
    ],
    'updatedBy' => [
        ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_INT
    ],
];