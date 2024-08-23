<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\infra\database\mysql\PublicationsModel;
use plugse\server\infra\traits\PublicationCopy;

class CopyUses extends AbstractUses
{
    use PublicationCopy;

    public function findManyByQuery(string $query): array
    {
        $publicationModel = new PublicationsModel();
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

    public function create(Entity $entity, array $subqueries = []): Entity
    {
        $publication = (new PublicationUses(new PublicationsModel()))->findOneById($entity->publicationId);

        $copy = $this->getCopy(
            date('Y', strtotime($publication->createdAt)),
            $publication->createdAt,
            $publication->createdBy,
            count($publication->copyList) + 1
        );
        $copy->publicationId = $entity->publicationId;

        $response = parent::create($copy);
        $response->publication = $publication;

        return $response;
    }
}
