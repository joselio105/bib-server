<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\HasMany;
use plugse\server\core\infra\database\relations\Relations;

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

    protected function setRelations()
    {
        $this->relations = [
            'copies' => new HasMany('publicationId', new CopyModel),
        ];
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        $entity = parent::findOne($whereClauses, $values, $fields);
        $entity = (new Relations($this))->hasManyOnEntity('copies', $entity);

        return $entity;
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        $search = parent::findMany($whereClauses, $values, $fields);
        $response = (new Relations($this))->hasManyOnArray('copies', $search);

        return $response;
    }
}
