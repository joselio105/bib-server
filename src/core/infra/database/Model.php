<?php

namespace plugse\server\core\infra\database;

use plugse\server\core\app\entities\Entity;

interface Model
{
    public function findMany();
    public function findOne();
    public function create(Entity $entity): Entity;
    // public function update();
    // public function delete();
}
