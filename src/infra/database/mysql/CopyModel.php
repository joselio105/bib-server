<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Copy;
use plugse\server\app\entities\User;
use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\Read;
use plugse\server\core\infra\database\mysql\InnerJoin;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\HasMany;
use plugse\server\core\infra\database\relations\RelationHasMany;
use plugse\server\core\infra\database\relations\RelationsBelongsTo;

class CopyModel extends ModelMysql
{
    protected function setEntity(): void
    {
        $this->entity = Copy::class;
    }

    protected function setTableName(): void
    {
        $this->tableName = 'copy';
    }

    protected function setFields()
    {
        $this->fields = [
            'copy.id' => 'id',
            'copy.registrationCode' => 'registrationCode',
            'copy.publicationId' => 'publication',
            'copy.createdAt' => 'createdAt',
            'copy.createdBy' => 'createdBy',
            'copy.updatedAt' => 'updatedAt',
            'copy.updatedBy' => 'updatedBy',
        ];
    }


    protected function buildQueryRead(string $whereClauses, array $values = [], array $fields = []): Read
    {
        $publicationsModel = new PublicationsModel();
        $publicationTable = $publicationsModel->getTableName();
        $userModel = new UserModel();

        $read = parent::buildQueryRead($whereClauses);
        $read
        ->setInnerJoin(new InnerJoin(
            $publicationsModel,
            $this->getTableName() . '.publicationId',
            [
                'PUB.id' => 'pub_id',
                'PUB.title' => 'pub_title',
                'PUB.originalTitle' => 'pub_originalTitle',
                'PUB.subTitle' => 'pub_subTitle',
                'PUB.subjects' => 'pub_subjects',
                'PUB.authors' => 'pub_authors',
                'PUB.themeCode' => 'pub_themeCode',
            ],
            'PUB'
        ))
        ->setInnerJoin(new InnerJoin(
            $userModel,
            $this->getTableName() . '.createdBy',
            [
                'CREATOR.id' => 'creator_id',
                'CREATOR.name' => 'creator_name',
                'CREATOR.email' => 'creator_email',
            ],
            'CREATOR'
        ))
        ->setInnerJoin(new InnerJoin(
            $userModel,
            $this->getTableName() . '.updatedBy',
            [
                'UPDATOR.id' => 'updator_id',
                'UPDATOR.name' => 'updator_name',
                'UPDATOR.email' => 'updator_email',
            ],
            'UPDATOR'
        ));

        return $read;
    }

    protected function formatFindOne(Entity $entity): Entity
    {
        $entity = $this->formatFindMany($entity);
        $entity = (new RelationHasMany($entity))->hasManyOnEntity(new HasMany('copyId', new LoanModel()), 'loans');

        return $entity;
    }

    protected function formatFindMany(Entity $entity): Entity
    {
        $entity = parent::formatFindMany($entity);
        $entity = (new RelationsBelongsTo($entity, 'pub_', new Publication()))->get('publication');
        $entity = (new RelationsBelongsTo($entity, 'creator_', new User()))->get('createdBy');
        $entity = (new RelationsBelongsTo($entity, 'updator_', new User()))->get('updatedBy');

        return $entity;
    }
}
