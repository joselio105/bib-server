<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\User;
use plugse\server\core\infra\database\mysql\ModelMysql;

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
}
