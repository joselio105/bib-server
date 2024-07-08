<?php

namespace plugse\server\core\app\uses;

use Exception;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\errors\EntityNotFoundError;
use plugse\server\core\infra\database\Model;

abstract class AbstractUses
{
    protected Model $model;

    abstract public function findManyByQuery(string $query): array;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function __call($name, $arguments)
    {
        $functionStart = 'findOneBy';
        $unionFields = 'And';

        $findBy = str_starts_with($name, $functionStart);

        if (!$findBy) {
            throw new Exception("The function name must starts with {$functionStart}");
        }

        $fields = substr($name, strlen($functionStart));
        $fields = array_map(
            function ($field) {
                return lcfirst($field);
            },
            explode($unionFields, $fields)
        );

        $values = array_combine($fields, $arguments);
        if (!$values) {
            throw new Exception('The number of fields and values must be the same');
        }

        $response = $this->findOneBy($values);

        return $response;
    }

    protected function findOneBy(array $values)
    {
        $where = [];
        foreach (array_keys($values) as $key) {
            array_push($where, "$key = :{$key}");
        }
        $whereClauses = implode(' AND ', $where);

        $response = $this->model->findOne($whereClauses, $values);

        if ($response->has($this->model->getPrimaryKey())) {
            return $response;
        }

        throw new EntityNotFoundError($response::class, $values[array_keys($values)[0]]);
    }

    public function create(Entity $entity, array $subqueries = []): Entity
    {
        return $this->model->create($entity, $subqueries);
    }

    public function update(string $id, Entity $entity): Entity
    {
        return $this->model->update($id, $entity);
    }
}
