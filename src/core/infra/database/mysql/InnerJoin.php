<?php

namespace plugse\server\core\infra\database\mysql;

use plugse\server\core\infra\database\Model;

class InnerJoin
{
    public function __construct(
        public readonly Model $model,
        public readonly string $on,
        public readonly array $fields
    ) {
    }
}
