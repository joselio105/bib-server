<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\infra\database\Model;

class HasMany
{
    public function __construct(
        public readonly string $foreignKey,
        public readonly Model $model,
        public readonly string $fields = '*'
    ) {
    }
}
