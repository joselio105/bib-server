<?php

namespace plugse\server\app\uses;

use plugse\server\app\entities\Copy;
use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\core\infra\database\mysql\Create;
use plugse\server\infra\database\mysql\CopyModel;

class PublicationUses extends AbstractUses
{
    public function findManyByQuery(string $query)
    {
        $values = [':query' => "%{$query}%"];
        $whereClauses = "title LIKE :query OR originalTitle LIKE :query OR subTitle LIKE :query OR subjects LIKE :query OR authors LIKE : query";

        return $this->model->findMany(
            $whereClauses, 
            $values, 
            'id, title, subTitle, originalTitle, authors, authorCode, themeCode, subjects, createdBy, updatedBy, createdAt, updatedAt'
        );
    }

    public function create(Entity $entity): Entity
    {
        $copies = [];
        $countCopies = ($entity->has('copies') and ($entity->copies > 1)) ? intval($entity->copies) : 1;
        
        $entity->unset('copies');
        for($i=0; $i<$countCopies; $i++) {
            $createdAt = date('Y-m-d H:i:s');
            $year = date('Y', strtotime($createdAt));
            $count = (new CopyModel)->count('createdAt LIKE :year', ['year' => "{$year}-%"]);
            
            $copy = new Copy;
            $copy->registrationCode = "bib.{$year}." . $count + $i + 1;
            $copy->createdAt = $createdAt;
            $copy->createdBy = $entity->createdBy;
            $copy->updatedBy = $entity->updatedBy;
            
            $query = (new Create)->setQuery('copy', $copy, 'publicationId')->getQuery();

            array_push($copies, $query);
        }

        return $this->model->create($entity, $copies);
    }
}
