<?php

namespace plugse\server\infra\traits;

use plugse\server\app\entities\Copy;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\core\infra\database\mysql\Create;

trait PublicationCopy
{
    public function getCopies(int $countCopies, string $year, int $createdBy, string $createdAt): array
    {
        $copies = [];

        $count = (new CopyModel())->count('createdAt LIKE :year', ['year' => "{$year}-%"]);

        for ($i = 0; $i < $countCopies; $i++) {
            $copy = $this->getCopy(
                $year,
                $createdAt,
                $createdBy,
                $count + $i + 1
            );
            $query = (new Create())->setQuery('copy', $copy, 'publicationId')->getQuery();

            array_push($copies, $query);
        }

        return $copies;
    }

    public function getCopy(string $year, string $createdAt, int $createdBy, int $count): Copy
    {
        $copy = new Copy();
        $copy->registrationCode = "bib.{$year}." . $count;
        $copy->createdAt = $createdAt;
        $copy->createdBy = $createdBy;
        $copy->updatedBy = $createdBy;

        return $copy;
    }
}
