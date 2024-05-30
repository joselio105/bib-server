<?php

namespace plugse\server\core\app\uses;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;

class AbstractUses
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(Entity $entity): Entity
    {
        return $this->model->create($entity);
    }

    public function findManyByQuery(string $query)
    {
        return $this->model->findMany();
    }
}
