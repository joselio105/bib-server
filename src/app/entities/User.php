<?php

namespace plugse\server\app\entities;

use plugse\server\core\app\entities\Entity;

/**
 * @property int id
 * @property string name
 * @property string password
 * @property string email
 * @property string phone
 * @property bool isAdmin
 * @property bool isActive
 */
class User extends Entity
{
    public function __set($name, $value)
    {
        if (in_array($name, ['isAdmin', 'isActive'])) {
            $this->attributes[$name] = ($value === '1');
        } else {
            parent::__set($name, $value);
        }
    }
}
