<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;

class PublicationsModel implements Model
{
    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        return [];
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        return new Publication;
    }

    public function create(Entity $entity): Entity
    {
        return new Entity;
    }

    public function update(string $id, Entity $entity): Entity
    {
        return new Entity;
    }
}
