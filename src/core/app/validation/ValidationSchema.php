<?php

namespace plugse\server\core\app\validation;

interface ValidationSchema
{
    public function getSchema(array $attributes): array;
}
