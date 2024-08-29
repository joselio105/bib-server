<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\uses\AbstractUses;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\infra\database\mysql\PublicationsModel;
use plugse\server\infra\database\mysql\UserModel;

class LoanUses extends AbstractUses
{
    public function findManyByQuery(string $query): array
    {
        $publicationModel = new PublicationsModel();
        $copyModel = new CopyModel();
        $userModel = new UserModel();

        $values = [':query' => "%{$query}%"];
        $fields = [
            "{$copyModel->getTableName()}.registrationCode",
            "{$publicationModel->getTableName()}.title",
            "{$publicationModel->getTableName()}.originalTitle",
            "{$publicationModel->getTableName()}.subTitle",
            "{$publicationModel->getTableName()}.subjects",
            "{$publicationModel->getTableName()}.authors",
            "{$publicationModel->getTableName()}.themeCode",
            "{$userModel->getTableName()}.name",
            "{$userModel->getTableName()}.email",
            "{$userModel->getTableName()}.phone",
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
