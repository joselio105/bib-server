<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\app\entities\Entity;

class RelationsBelongsTo
{
    private Entity $entity;
    private string $prefix;
    private Entity $belongedEntity;

    public function __construct(
        Entity $entity,
        string $prefix,
        Entity $belongedEntity
    ) {
        $this->entity = $entity;
        $this->prefix = $prefix;
        $this->belongedEntity = $belongedEntity;
    }

    public function get(string $name): Entity
    {
        $entityName = get_class($this->entity);
        $entity = new $entityName();

        foreach ($this->entity->getAttributes() as $key => $value) {
            if (substr($key, 0, strlen($this->prefix)) === $this->prefix) {
                $belongedKey = substr($key, strlen($this->prefix));
                $this->belongedEntity->$belongedKey = $value;
            } else {
                $entity->$key = $value;
            }
        }
        if (!empty($this->belongedEntity->getAttributes())) {
            $entity->$name = $this->belongedEntity;
        }

        return $entity;
    }
}
