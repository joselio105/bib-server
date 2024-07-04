<?php

namespace plugse\server\app\mappers;

use plugse\server\core\app\mappers\Mapper;

class PublicationMapper implements Mapper
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $subTitle,
        public readonly ?string $originalTitle,
        public readonly ?string $originalLanguage,
        public readonly string $publicationLanguage,
        public readonly ?string $authors,
        public readonly ?string $translator,
        public readonly ?string $isbn,
        public readonly string $authorCode,
        public readonly string $themeCode,
        public readonly ?string $publisher,
        public readonly ?string $pubDate,
        public readonly ?string $pubOriginalDate,
        public readonly ?string $pubPlace,
        public readonly ?string $subjects,
        public readonly ?int $pagesNumber,
        public readonly ?string $edition,
        public readonly ?string $volume,
        public readonly string $createdAt,
        public readonly int $createdBy,
        public readonly string $updatedAt,
        public readonly int $updatedBy,
        public array $copies = []
    )
    {}
    
    public function setCopies(array $copies)
    {
        $this->copies = [];
        foreach($copies as $copy) {
            $mapper = new CopyMapper(
                $copy->id,
                $copy->publicationId,
                $copy->registrationCode,
                $copy->createdAt,
                $copy->createdBy,
                $copy->updatedAt,
                $copy->updatedBy,
            );
            
            array_push($this->copies, $mapper);
        }
    }
}
