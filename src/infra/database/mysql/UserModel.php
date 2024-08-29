<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\User;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\HasMany;
use plugse\server\core\infra\database\relations\RelationHasMany;

class UserModel extends ModelMysql
{
    protected function setTableName(): void
    {
        $this->tableName = 'user';
    }

    protected function setEntity(): void
    {
        $this->entity = User::class;
    }

    protected function setHashes()
    {
        $this->hashes = ['password'];
    }

    protected function formatFindOne(Entity $entity): Entity
    {
        $entity = parent::formatFindOne($entity);
        $entity = (new RelationHasMany($entity))->hasManyOnEntity(
            new HasMany('userId', new LoanModel()),
            'loans'
        );

        return $entity;
    }
}
