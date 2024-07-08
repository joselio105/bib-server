<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\ModelMysql;

class Relations
{
    private string $foreignKey;
    private string $primaryKey;

    public function __construct(
        private readonly ModelMysql $model,
    ) {
    }

    public function hasManyOnEntity(string $field, Entity $entity): Entity
    {
        $relation = $this->model->getRelationHasMany($field);
        $this->primaryKey = $relation->model->getPrimaryKey();
        $this->foreignKey = $relation->foreignKey;

        if (!$entity->has($this->primaryKey)) {
            return $entity;
        }

        $hasMany = $relation->model->findMany(
            "{$this->foreignKey}=:{$this->foreignKey}",
            [":{$this->foreignKey}" => $entity->$this->primaryKey],
            $relation->fields
        );

        $entity->$field = $hasMany;

        return $entity;
    }

    public function hasManyOnArray(string $field, array $entities): array
    {
        $relation = $this->model->getRelationHasMany($field);
        $where = $this->getHasManyWhere($entities, $relation);

        $hasMany = $relation->model->findMany(
            $where['clauses'],
            $where['values'],
            $relation->fields
        );

        return $this->assemblyHasMany($entities, $field, $hasMany);
    }

    private function assemblyHasMany(array $entities, string $field, array $relatedEntities): array
    {
        foreach ($entities as $entity) {
            $entity->$field = array_filter($relatedEntities, function ($relatedEntity) use ($entity) {
                $primaryKey = $this->primaryKey;
                $foreignKey = $this->foreignKey;

                return $relatedEntity->$foreignKey === $entity->$primaryKey;
            });
        }

        return $entities;
    }

    private function getHasManyWhere(array $entities, HasMany $relation): array
    {
        $response = [
            'clauses' => [],
            'values' => [],
        ];

        $this->primaryKey = $relation->model->getPrimaryKey();
        $this->foreignKey = $relation->foreignKey;

        foreach ($entities as $key => $entity) {
            array_push($response['clauses'], "{$this->foreignKey}=:{$this->foreignKey}_{$key}");
            $primaryKey = $this->primaryKey;
            $response['values'][":{$this->foreignKey}_{$key}"] = $entity->$primaryKey;
        }

        return [
            'clauses' => implode(' OR ', $response['clauses']),
            'values' => $response['values'],
        ];
    }
}
