<?php

namespace plugse\server\app\schemas;

use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\app\validations\validations\MustBeCutter;
use plugse\server\app\validations\validations\MusBeAuthorList;
use plugse\server\core\app\validation\validations\MustBeInt;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\core\app\validation\validations\MustBeString;
use plugse\server\core\app\validation\validations\MustHaveLenght;

class PublicationSchema extends ValidationSchema
{
    public function getSchema(array $attributes): array
    {
        return [
            'title' => [
                IsRequired::make($attributes, 'title'),
                MustBeString::make($attributes, 'title'),

            ],
            'subTitle' => [
                MustBeString::make($attributes, 'subTitle'),

            ],
            'originalTitle' => [
                MustBeString::make($attributes, 'originalTitle'),

            ],
            'originalLanguage' => [
                MustBeString::make($attributes, 'originalLanguage'),

            ],
            'publicationLanguage' => [
                IsRequired::make($attributes, 'publicationLanguage'),
                MustBeString::make($attributes, 'publicationLanguage'),

            ],
            'authors' => [
                MusBeAuthorList::make($attributes, 'authors'),
            ],
            'translator' => [
                MustBeString::make($attributes, 'translator'),

            ],
            'isbn' => [
                MustBeString::make($attributes, 'isbn'),
                MustHaveLenght::make(
                    $attributes,
                    'isbn',
                    9,
                    MustHaveLenght::LENGTH_GREATHER
                ),

            ],
            'authorCode' => [
                IsRequired::make($attributes, 'authorCode'),
                MustBeString::make($attributes, 'authorCode'),
                MustBeCutter::make($attributes, 'authors'),

            ],
            'themeCode' => [
                IsRequired::make($attributes, 'themeCode'),
                MustBeString::make($attributes, 'themeCode'),
                MustHaveLenght::make(
                    $attributes,
                    'themeCode',
                    2,
                    MustHaveLenght::LENGTH_GREATHER
                ),

            ],
            'publisher' => [
                MustBeString::make($attributes, 'publisher'),

            ],
            'pubDate' => [
                MustBeString::make($attributes, 'pubDate'),

            ],
            'pubOriginalDate' => [
                MustBeString::make($attributes, 'pubOriginalDate'),

            ],
            'pubPlace' => [
                MustBeString::make($attributes, 'pubPlace'),

            ],
            'subjects' => [
                MustBeString::make($attributes, 'subjects'),

            ],
            'pagesNumber' => [
                MustBeString::make($attributes, 'pagesNumber'),

            ],
            'edition' => [
                MustBeString::make($attributes, 'edition'),

            ],
            'volume' => [
                MustBeString::make($attributes, 'volume'),

            ],
            'copies' => [
                MustBeInt::make($attributes, 'copies'),
            ],
        ];
    }
}
