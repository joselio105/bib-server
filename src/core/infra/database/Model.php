<?php

namespace plugse\server\core\infra\database;

interface Model
{
    public function findMany();
    public function findOne();
    // public function create();
    // public function update();
    // public function delete();
}
