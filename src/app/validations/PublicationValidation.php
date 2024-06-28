<?php

use plugse\server\core\app\validation\ValidationTypes;

return [
    'title' => [ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING],
    'subTitle' => [ValidationTypes::MUST_BE_STRING],
    'originalTitle' => [ValidationTypes::MUST_BE_STRING],
    'publicationLanguage' => [ValidationTypes::IS_REQUIRED, ValidationTypes::MUST_BE_STRING],
    'originalLanguage' => [ValidationTypes::MUST_BE_STRING],
    'translator' => [ValidationTypes::MUST_BE_STRING],
    'authors' => [ValidationTypes::MUST_BE_AUTHORS],
    'authorCode' => [ValidationTypes::MUST_BE_CUTTER],
    'isbn' => [ValidationTypes::MUST_BE_STRING, ValidationTypes::MUST_HAVE_LENGTH_GREATHER_THAN, 9],
    'publisher' => [ValidationTypes::MUST_BE_STRING],
    'pubDate' => [ValidationTypes::MUST_BE_STRING],
    'pubOriginalDate' => [ValidationTypes::MUST_BE_STRING],
    'pubPlace' => [ValidationTypes::MUST_BE_STRING],
    'subjects' => [ValidationTypes::MUST_BE_STRING],
    'pagesNumber' => [ValidationTypes::MUST_BE_STRING],
    'edition' => [ValidationTypes::MUST_BE_STRING],
    'volume' => [ValidationTypes::MUST_BE_STRING],
    'copies' => [ValidationTypes::MUST_BE_INT]
];