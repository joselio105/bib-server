<?php

namespace plugse\server\core\app\uses;

use plugse\server\core\infra\database\Model;

class AbstractUses
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findManyByQuery(string $query)
    {
        return $this->model->findMany();
    }
}
