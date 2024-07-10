<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\uses\AbstractUses;
use plugse\server\infra\database\mysql\PublicationsModel;

class CopyUses extends AbstractUses
{
    public function findManyByQuery(string $query): array
    {
        $publicationModel = new PublicationsModel;
        $values = [':query' => "%{$query}%"];
        $fields = [
            "{$this->model->getTableName()}.registrationCode",
            "{$publicationModel->getTableName()}.title",
            "{$publicationModel->getTableName()}.originalTitle",
            "{$publicationModel->getTableName()}.subTitle",
            "{$publicationModel->getTableName()}.subjects",
            "{$publicationModel->getTableName()}.authors",
            "{$publicationModel->getTableName()}.themeCode",
        ];

        $whereClauses = [];
        foreach ($fields as $field) {
            array_push($whereClauses, "{$field} LIKE :query");
        }

        return $this->model->findMany(
            implode(' OR ', $whereClauses),
            $values,
        );
    }
}
