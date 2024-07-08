<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Copy;
use plugse\server\core\infra\database\mysql\ModelMysql;

class CopyModel extends ModelMysql
{
    protected function setEntity(): void
    {
        $this->entity = Copy::class;
    }

    protected function setTableName(): void
    {
        $this->tableName = 'copy';
    }
}
