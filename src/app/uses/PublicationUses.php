<?php

namespace plugse\server\app\uses;

use Exception;
use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\infra\traits\PublicationCopy;

class PublicationUses extends AbstractUses
{
    use PublicationCopy;

    public function findManyByQuery(string $query): array
    {
        $values = [':query' => "%{$query}%"];
        $fields = ['title', 'originalTitle', 'subTitle', 'subjects', 'authors', 'themeCode'];
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
        $this->checkPublication($entity);

        $copies = $this->getCopies(
            ($entity->has('copies') and ($entity->copies > 1)) ? intval($entity->copies) : 1,
            date('Y', strtotime($entity->createdAt)),
            $entity->createdBy,
            $entity->createdAt
        );
        $entity->unset('copies');

        return parent::create($entity, $copies);
    }

    private function checkPublication(Publication $publication): void
    {
        $filedsToCheck = ['title', 'authorCode', 'themeCode', 'publisher', 'edition', 'volume'];
        $whereClauses = [];
        $values = [];

        foreach ($filedsToCheck as $field) {
            if ($publication->has($field)) {
                $value = $publication->$field;
                if (strlen($value) > 0) {
                    array_push($whereClauses, "{$field}=:{$field}");
                    $values[":{$field}"] = $value;
                } else {
                    array_push($whereClauses, "{$field} IS NULL");
                }
            }
        }

        $count = $this->model->count(implode(' AND ', $whereClauses), $values);

        if ($count > 0) {
            http_response_code(300);

            throw new Exception('Já temos essa publicação registrada');
        }
    }
}
