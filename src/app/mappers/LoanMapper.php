<?php

namespace plugse\server\app\mappers;

use plugse\server\app\entities\Copy;
use plugse\server\app\entities\Publication;
use plugse\server\app\entities\User;
use plugse\server\core\app\mappers\Mapper;

class LoanMapper extends Mapper
{
    public function setCopy(Copy $copy, Publication $publication)
    {
        $mapper = new CopyMapper($copy);
        $mapper->setPublication($publication);
        $this->copy = $mapper->__serialize();
    }

    public function setUser(User $user)
    {
        $mapper = new UserMapper($user);
        $this->user = $mapper->__serialize();
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
