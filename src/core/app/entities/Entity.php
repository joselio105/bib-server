<?php

namespace plugse\server\core\app\entities;

use Exception;

abstract class Entity
{
    private array $attributes;

    public function __construct(private array $validations=[])
    {}

    public function __get($name)
    {
        if(!key_exists($name, $this->attributes)){
            http_response_code(404);
            $entity = self::class;
            throw new Exception("O atributo '{$name}' não foi existe na entidade '{$entity}'");
        }

        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function has(string $attribute): bool
    {
        return key_exists($attribute, $this->attributes);
    }

    public function getValidation(): array
    {
        return $this->validations;
    }

    public function getAttributes(): array
    {        
        return $this->attributes;
    }
}
