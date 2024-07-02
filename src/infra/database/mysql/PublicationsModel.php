<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;
use plugse\server\core\infra\database\mysql\ModelMysql;

class PublicationsModel extends ModelMysql
{
    protected function setTableName(): void
    {
        $this->tableName = 'publication';
    }

    protected function setEntity(): void
    {
        $this->entity = Publication::class;
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        return [];
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        return new Publication;
    }

    public function update(string $id, Entity $entity): Entity
    {
        return new Entity;
    }
}
