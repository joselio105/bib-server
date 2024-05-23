<?php

namespace plugse\server\app\entities;

use plugse\server\core\app\entities\Entity;

class Publication implements Entity
{
    public int $id;
    public string $title;
    public string $subTitle;
    public string $originalTitle;
    public string $originalLanguage;
    public string $publicationLanguage;
    public array $authors;
    public string $translator;
    public string $isbn;
    public string $authorCode;
    public string $themeCode;
    public string $publisher;
    public string $pubDate;
    public string $pubOriginalDate;
    public string $pubPlace;
    public string $subjects;
    public int $pagesNumber;
    public string $edition;
    public string $volume;
    public string $createdAt;
    public string $createdBy;
    public string $updatedAt;
    public string $updatedBy;
}
