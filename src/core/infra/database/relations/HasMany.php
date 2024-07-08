<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\infra\database\mysql\ModelMysql;

class HasMany
{
    public function __construct(
        public readonly string $foreignKey,
        public readonly ModelMysql $model,
        public readonly string $fields = '*'
    )
    {}
}
