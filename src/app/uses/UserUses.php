<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\uses\AbstractUses;

class UserUses extends AbstractUses
{
    public function findManyByQuery(string $query)
    {
        $values = ['query' => $query];
        $whereClauses = "name LIKE '%:query%' OR email LIKE '%:query%' OR phone LIKE '%:query%'";

        return $this->model->findMany($whereClauses, $values, 'id, name, email, phone, isActive, isAdmin');
    }
}
