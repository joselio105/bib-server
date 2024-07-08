<?php

namespace plugse\server\core\infra\database;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\relations\HasMany;

interface Model
{
    public function getPrimaryKey(): string;
    public function getTableName(): string;
    public function getRelations(string $type): array;
    public function getRelationHasMany(string $field): HasMany;

    public function clearTable();
    public function findMany(string $whereClauses, array $values, string $fields = '*'): array;
    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity;
    public function count(string $whereClauses, array $values = []): int;
    public function create(Entity $entity, array $subqueries = []): Entity;
    public function update(string $id, Entity $entity): Entity;
    // public function delete();
}
