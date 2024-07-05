<?php

namespace plugse\server\app\mappers;

use plugse\server\core\app\mappers\Mapper;

class PublicationMapper extends Mapper
{    
    public function setCopies(array $copies)
    {
        $this->copies = [];
        foreach($copies as $copy) {
            $mapper = new CopyMapper($copy);
            
            array_push($this->copies, $mapper);
        }
    }
}
