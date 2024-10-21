<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\exceptions\IsUniqueError;
use plugse\server\core\app\validation\Validation;
use plugse\server\core\infra\database\Model;

class IsUnique
{
    public static function make(array $attributes, $attributeName, Model $model, string $field = null)
    {
        $value = $attributes[$attributeName];
        $field = is_null($field) ? $attributeName : $field;
        $where = "{$field}=:{$field}";
        $values = [$field => $value];

        $primaryKey = $model->getPrimaryKey();
        if (key_exists($primaryKey, $attributes)) {
            $where = $where . " AND {$primaryKey}<>:{$primaryKey}";
            $values[$primaryKey] = $attributes[$primaryKey];
        }
        $entity = $model->findOne($where, $values);

        return new Validation(
            !$entity->has($field),
            new IsUniqueError($attributeName, $value, $model->getTableName())
        );
    }
}
