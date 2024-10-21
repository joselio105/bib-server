<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\infra\database\Model;

class HasMany
{
    public string $foreignKey;
    public Model $model;
    public array $fields;

    public function __construct(
        string $foreignKey,
        Model $model,
        array $fields = []
    ) {
        $this->foreignKey = $foreignKey;
        $this->model = $model;
        $this->fields = $fields;
    }
}
