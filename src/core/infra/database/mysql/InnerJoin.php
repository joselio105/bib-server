<?php

namespace plugse\server\core\infra\database\mysql;

use plugse\server\core\infra\database\Model;
use plugse\server\app\validations\validations\MustBeForeignKey;

class InnerJoin
{
    public Model $model;
    public string $foreignKey;
    public array $fields;
    public string $tableAlias = '';

    public function __construct(
        Model $model,
        string $foreignKey,
        array $fields,
        string $tableAlias = ''
    ) {
        $this->model = $model;
        $this->foreignKey = $foreignKey;
        $this->fields = $fields;
        $this->tableAlias = $tableAlias;
    }

    public function __toString()
    {
        MustBeForeignKey::make(['foreignKey' => $this->foreignKey], 'foreignKey')->validate();

        $joinTableName = $this->model->getTableName();
        $joinPrimaryKey = $this->model->getPrimaryKey();

        if (strlen($this->tableAlias) === 0) {
            return "\nINNER JOIN {$joinTableName} ON {$joinTableName}.{$joinPrimaryKey}={$this->foreignKey}";
        }

        return "\nINNER JOIN {$joinTableName} AS {$this->tableAlias} ON {$this->tableAlias}.{$joinPrimaryKey}={$this->foreignKey}";
    }
}
