<?php

namespace plugse\server\core\infra\database;

use plugse\server\core\app\entities\Entity;

interface Model
{
    public function findMany(string $whereClauses, array $values, string $fields = '*') : array;
    public function findOne(string $whereClauses, array $values, string $fields = '*') : Entity;
    public function count(string $whereClauses, array $values = []): int;
    public function create(Entity $entity, array $subqueries = []): Entity;
    public function update(string $id, Entity $entity): Entity;
    // public function delete();
}
