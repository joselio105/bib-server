<?php

namespace plugse\server\app\mappers;

use plugse\server\app\entities\Publication;
use plugse\server\core\app\mappers\Mapper;

class CopyMapper implements Mapper
{
    public function __construct(
        public readonly int $id,
        public readonly int $publicationId,        
        public readonly string $registrationCode,
        public readonly string $createdAt,
        public readonly string $createdBy,
        public readonly string $updatedAt,
        public readonly string $updatedBy,
        public ?PublicationMapper $publication = null
    )
    {}

    public function setPublication(Publication $publication)
    {
        $this->publication = new PublicationMapper(            
            $publication->id,
            $publication->title,
            $publication->subTitle,
            $publication->originalTitle,
            $publication->originalLanguage,
            $publication->publicationLanguage,
            $publication->authors,
            $publication->translator,
            $publication->isbn,
            $publication->authorCode,
            $publication->themeCode,
            $publication->publisher,
            $publication->pubDate,
            $publication->pubOriginalDate,
            $publication->pubPlace,
            $publication->subjects,
            $publication->pagesNumber,
            $publication->edition,
            $publication->volume,
            $publication->createdAt,
            $publication->createdBy,
            $publication->updatedAt,
            $publication->updatedBy,
        );
    }
}
