<?php

namespace plugse\server\core\infra\database\mysql;

use plugse\server\core\app\validation\Validations;
use plugse\server\core\infra\database\Model;

class InnerJoin
{
    public function __construct(
        public readonly Model $model,
        public readonly string $foreignKey,
        public readonly array $fields,
        public readonly string $tableAlias = ''
    ) {
    }

    public function __toString()
    {
        Validations::mustBeForeignKey(['foreignKey' => $this->foreignKey], 'foreignKey');

        $joinTableName = $this->model->getTableName();
        $joinPrimaryKey = $this->model->getPrimaryKey();

        if (strlen($this->tableAlias) === 0) {
            return "\nINNER JOIN {$joinTableName} ON {$joinTableName}.{$joinPrimaryKey}={$this->foreignKey}";
        }

        return "\nINNER JOIN {$joinTableName} AS {$this->tableAlias} ON {$this->tableAlias}.{$joinPrimaryKey}={$this->foreignKey}";
    }
}
