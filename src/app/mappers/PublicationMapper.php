<?php

namespace plugse\server\app\mappers;

use plugse\server\core\app\mappers\Mapper;

class PublicationMapper implements Mapper
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $subTitle,
        public ?string $originalTitle,
        public ?string $originalLanguage,
        public string $publicationLanguage,
        public ?string $authors,
        public ?string $translator,
        public ?string $isbn,
        public string $authorCode,
        public string $themeCode,
        public ?string $publisher,
        public ?string $pubDate,
        public ?string $pubOriginalDate,
        public ?string $pubPlace,
        public ?string $subjects,
        public ?int $pagesNumber,
        public ?string $edition,
        public ?string $volume,
        public string $createdAt,
        public int $createdBy,
        public string $updatedAt,
        public int $updatedBy
    )
    {}
}
