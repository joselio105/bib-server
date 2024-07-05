<?php

namespace plugse\server\app\uses;

use Exception;
use plugse\server\core\app\uses\AbstractUses;

class CopyUses extends AbstractUses
{
    public function findManyByQuery(string $query): array
    {
        $values = [':query' => "%{$query}%"];
        $fields = ['registrationCode', 'pub_title', 'pub_originalTitle', 'pub_subTitle', 'pub_subjects', 'pub_author', 'pub_themeCode'];
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
