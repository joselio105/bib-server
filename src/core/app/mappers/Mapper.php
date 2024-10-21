<?php

namespace plugse\server\core\app\mappers;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\errors\AttributeClassNotFoundError;

abstract class Mapper
{
    protected array $attributes;
    protected Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        foreach ($entity->getAttributes() as $key => $value) {
            if (!(is_object($value) or is_array($value))) {
                $this->setValue($key);
            }
        }
    }

    public function __serialize(): array
    {
        return $this->attributes;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        throw new AttributeClassNotFoundError($name, self::class, get_class($this));
    }

    protected function setValue(string $key)
    {
        if ($this->entity->has($key)) {
            $this->attributes[$key] = $this->entity->$key;
        }
    }

    protected function setMapper(string $key)
    {
        // var_dump($this->entity, method_exists($this->entity, 'getMapper'));
        // die;
        $mapper = $this->entity->getMapper();
        $this->attributes[$key] = (new $mapper($this->entity))->__serialize();
    }

    protected function setArray($key)
    {
        $this->attributes[$key] = [];

        foreach ($this->entity->$key as $entity) {
            $mapper = $entity->getMapper();
            array_push($this->attributes[$key], (new $mapper($entity))->__serialize());
        }
    }
}
