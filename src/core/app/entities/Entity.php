<?php

namespace plugse\server\core\app\entities;

use ReflectionClass;
use plugse\server\core\errors\ClassNotFoundError;
use plugse\server\core\errors\AttributeClassNotFoundError;

abstract class Entity
{
    protected array $attributes;

    public function __get($name)
    {
        if (key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        throw new AttributeClassNotFoundError($name, get_class($this), get_class($this));
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

    public function getValidation(array $body): array
    {
        $reflect = new ReflectionClass($this);
        $validationSchema = str_replace('\\entities\\', '\\schemas\\', $reflect->getName()) . 'Schema';

        return class_exists($validationSchema) ? (new $validationSchema())->getSchema($body) : [];
    }

    public function getMapper(): string
    {
        $reflect = new ReflectionClass($this);
        $mapperClass = str_replace('\\entities\\', '\\mappers\\', $reflect->getName()) . 'Mapper';

        if (!class_exists($mapperClass)) {
            throw new ClassNotFoundError($mapperClass);
        }

        return $mapperClass;
    }

    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }
}
