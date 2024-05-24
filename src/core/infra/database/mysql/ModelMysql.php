<?php

namespace plugse\server\core\infra\database\mysql;

use Exception;
use PDO;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\helpers\File;
use plugse\server\core\infra\database\Model;

abstract class ModelMysql implements Model
{
    private array $dbSettings;
    private PDO $connection;
    protected string $tableName;
    // protected QueryBuilder $querySelectMany;
    // private array|Entity $found;
    // public array $relations;
    protected array $indexUniques;
    protected array $indexForeigns;
    protected string $entity;
    protected string $mapper;

    public function __construct()

    {
        $this->dbSettings = File::getProperty(SECRET_KEY_FILE, 'db');
        $this->setTableName();
        // $this->setQuerySelectMany();
        $this->connection = Connection::getInstance($this->dbSettings);
        // $this->setEntity();
        // $this->setMapper();
        // $this->setRelations();
        $this->setIndexUniques();
        $this->setIndexForeigns();
    }

    abstract protected function setTableName(): void;

    // protected function setQuerySelectMany()
    // {
    //     $query = new QueryBuilder($this->getTableName(), []);
    //     $this->querySelectMany = $query;
    // }

    protected function setIndexUniques()
    {
        $this->indexUniques = [];
    }

    protected function setIndexForeigns()
    {
        $this->indexForeigns = [];
    }

    public function getTableName(): string
    {
        $table_prefix = $this->dbSettings['prefix'];;

        return "{$table_prefix}{$this->tableName}";
    }


    public function create(Entity $entity): int
    {
        
        // TableIndexes::checkIndexUniques($entity, static::class);
        // $entity = $this->hashPasswordIfExists($entity, $entity->getValidationSchema());

        try {
            $create = new Create($this->connection);
            $response = $create->setQuery($this->getTableName(), $entity)->run();

            return $response->id;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function findMany()
    {
        
    }

    public function findOne()
    {
        
    }
}
