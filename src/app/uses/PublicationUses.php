<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\uses\AbstractUses;

class PublicationUses extends AbstractUses
{
    public function findManyByQuery(string $query)
    {
        $values = [':query' => "%{$query}%"];
        $whereClauses = "title LIKE :query OR originalTitle LIKE :query OR subTitle LIKE :query OR subjects LIKE :query OR authors LIKE : query";

        return $this->model->findMany(
            $whereClauses, 
            $values, 
            'id, title, subTitle, originalTitle, authors, authorCode, themeCode, subjects, createdBy, updatedBy, createdAt, updatedAt'
        );
    }
}
