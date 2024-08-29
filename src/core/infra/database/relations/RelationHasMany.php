<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\app\entities\Entity;

class RelationHasMany
{
    private Entity $entity;
    private string $foreignKey;
    private string $primaryKey;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function hasManyOnEntity(HasMany $relation, string $fieldName): Entity
    {
        $this->primaryKey = $relation->model->getPrimaryKey();
        $this->foreignKey = $relation->foreignKey;

        if (!$this->entity->has($this->primaryKey)) {
            return $this->entity;
        }

        $primaryKey = $this->primaryKey;
        $hasMany = $relation->model->findMany(
            "{$relation->model->getTableName()}.{$this->foreignKey}=:{$this->foreignKey}",
            [$this->foreignKey => $this->entity->$primaryKey],
            $relation->fields
        );

        $this->entity->$fieldName = $hasMany;

        return $this->entity;
    }

    /* public function hasManyOnArray(HasMany $relation, string $fieldName): array
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
    } */
}
