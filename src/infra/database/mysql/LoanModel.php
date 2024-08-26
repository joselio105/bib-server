<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Copy;
use plugse\server\app\entities\Loan;
use plugse\server\app\entities\Publication;
use plugse\server\app\entities\User;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\Read;
use plugse\server\core\infra\database\mysql\InnerJoin;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\RelationsBelongsTo;

class LoanModel extends ModelMysql
{
    protected function setEntity(): void
    {
        $this->entity = Loan::class;
    }

    protected function setTableName(): void
    {
        $this->tableName = 'loan';
    }

    protected function buildQueryRead(string $whereClauses, array $values, string $fields = '*'): Read
    {
        $userModel = new UserModel();
        $copyModel = new CopyModel();

        $read = parent::buildQueryRead($whereClauses, $values);
        $read->setFields([
            'loan.id' => 'id',
            'loan.userId' => 'userId',
            'loan.copyId' => 'copyId',
            'loan.loannedAt' => 'loannedAt',
            'loan.returnAt' => 'returnAt',
            'loan.returnedAt' => 'returnedAt',
            'loan.createdBy' => 'createdBy',
            'loan.updatedBy' => 'updatedBy',
            'loan.createdAt' => 'createdAt',
            'loan.updatedAt' => 'updatedAt',
        ])
        ->setInnerJoin(new InnerJoin(
            $userModel,
            $this->getTableName() . '.userId',
            [
                'USER.id' => 'user_id',
                'USER.name' => 'user_name',
                'USER.email' => 'user_email',
            ],
            'USER'
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
        ))
        ->setInnerJoin(new InnerJoin(
            $copyModel,
            $this->getTableName() . '.copyId',
            [
                'COPY.id' => 'copy_id',
                'COPY.registrationCode' => 'copy_registrationCode',
            ],
            'COPY'
        ))
        ->setInnerJoin(new InnerJoin(
            new PublicationsModel(),
            $copyModel->getTableName() . '.publicationId',
            [
                'PUB.id' => 'publication_id',
                'PUB.title' => 'publication_title',
                'PUB.authorCode' => 'publication_authorCode',
                'PUB.authors' => 'publication_authors',
            ],
            'PUB'
        ));

        return $read;
    }

    protected function formatEntity(Entity $entity): Entity
    {
        $entity = (new RelationsBelongsTo($entity, 'creator_', new User()))->get('createdBy');
        $entity = (new RelationsBelongsTo($entity, 'updator_', new User()))->get('updatedBy');
        $entity = (new RelationsBelongsTo($entity, 'user_', new User()))->get('user');
        $entity = (new RelationsBelongsTo($entity, 'copy_', new Copy()))->get('copy');
        $entity = (new RelationsBelongsTo($entity, 'publication_', new Publication()))->get('publication');

        return $entity;
    }
}
