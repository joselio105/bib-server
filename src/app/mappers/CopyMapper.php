<?php

namespace plugse\server\app\mappers;

use plugse\server\app\entities\Publication;
use plugse\server\app\entities\User;
use plugse\server\core\app\mappers\Mapper;

class CopyMapper extends Mapper
{
    public function setPublication(Publication $publication)
    {
        $mapper = new PublicationMapper($publication);
        $this->publication = $mapper->__serialize();
    }

    public function setCreator(User $user)
    {
        $mapper = new UserMapper($user);
        $this->createdBy = $mapper->__serialize();
    }

    public function setUpdator(User $user)
    {
        $mapper = new UserMapper($user);
        $this->updatedBy = $mapper->__serialize();
    }
}
