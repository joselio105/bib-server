<?php

namespace plugse\server\core\infra\database\relations;

use plugse\server\core\app\entities\Entity;

class RelationsBelongsTo
{
    public function __construct(
        private readonly Entity $entity,
        private readonly string $prefix,
        private readonly Entity $belongedEntity
    ) {
    }

    public function get(string $name): Entity
    {
        $entityName = get_class($this->entity);
        $entity = new $entityName;

        foreach ($this->entity->getAttributes() as $key => $value) {
            if (str_starts_with($key, $this->prefix)) {
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
