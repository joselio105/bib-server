<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\HasMany;
use plugse\server\core\infra\database\relations\RelationHasMany;

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

    protected function formatFindOne(Entity $entity): Entity
    {
        $entity = parent::formatFindOne($entity);
        $entity = (new RelationHasMany($entity))->hasManyOnEntity(new HasMany('publicationId', new CopyModel()), 'copyList');

        return $entity;
    }
}
