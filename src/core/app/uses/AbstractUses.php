<?php

namespace plugse\server\core\app\uses;

use Exception;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;
use plugse\server\core\errors\EntityNotFoundError;

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

        $findBy = substr($name, 0, strlen($functionStart)) === $functionStart;

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

        $class = get_class($response);

        throw new EntityNotFoundError($class, $values[array_keys($values)[0]]);
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
