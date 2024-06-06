<?php

use plugse\server\core\app\validation\ValidationTypes;

return [
    'title' => [ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING],
    'subTitle' => [ValidationTypes::MUST_BE_STRING],
    'originalTitle' => [ValidationTypes::MUST_BE_STRING],
    'publicationLanguage' => [ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING],
    'originalLanguage' => [ValidationTypes::MUST_BE_STRING],
    'translator' => [ValidationTypes::MUST_BE_STRING],
    'authors' => [ValidationTypes::MUST_BE_STRING],
    'authorCode' => [ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING],
    'isbn' => [ValidationTypes::MUST_BE_STRING],
    'publisher' => [ValidationTypes::MUST_BE_STRING],
    'pubDate' => [ValidationTypes::MUST_BE_STRING],
    'pubOriginalDate' => [ValidationTypes::MUST_BE_STRING],
    'pubPlace' => [ValidationTypes::MUST_BE_STRING],
    'subjects' => [ValidationTypes::MUST_BE_STRING],
    'pagesNumber' => [ValidationTypes::MUST_BE_STRING],
    'edition' => [ValidationTypes::MUST_BE_STRING],
    'volume' => [ValidationTypes::MUST_BE_STRING],
];