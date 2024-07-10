<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Loan;
use plugse\server\core\infra\database\mysql\ModelMysql;

class LoanModel extends ModelMysql
{

    protected function setEntity(): void
    {
        $this->entity = Loan::class;
    }

    protected function setTableName(): void
    {
        $this->tableName = 'loan';
    }
}
