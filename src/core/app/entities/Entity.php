<?php

namespace plugse\server\core\app\entities;

use plugse\server\core\errors\AttributeClassNotFoundError;

abstract class Entity
{
    private array $attributes;

    public function __construct(private array $validations = [])
    {
    }

    public function __get($name)
    {
        if (key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        throw new AttributeClassNotFoundError($name, self::class, $this::class);
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function has(string $attribute): bool
    {
        if (isset($this->attributes)) {
            return key_exists($attribute, $this->attributes);
        }

        return false;
    }

    public function unset(string $name)
    {
        if ($this->has($name)) {
            unset($this->attributes[$name]);
        }
    }

    public function getValidation(): array
    {
        return $this->validations;
    }

    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }
}
