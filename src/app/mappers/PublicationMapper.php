<?php

namespace plugse\server\app\mappers;

use plugse\server\core\app\mappers\Mapper;

class PublicationMapper extends Mapper
{
    public function setCopies(array $copies)
    {
        $copyList = [];

        foreach ($copies as $copy) {
            $mapper = new CopyMapper($copy);

            $mapper->setCreator($copy->createdBy);
            $mapper->setUpdator($copy->updatedBy);
            array_push($copyList, $mapper->__serialize());
        }

        $this->copyList = $copyList;
        $this->copies = count($this->copyList);
    }
}
