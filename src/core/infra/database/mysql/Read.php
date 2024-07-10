<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use PDOStatement;
use plugse\server\core\app\validation\Validations;
use plugse\server\core\errors\AttributeClassNotFoundError;

class Read
{
    private readonly string $tablename;
    private array $fields;
    private readonly string $countField;
    private readonly string $whereClauses;
    private array $innerJoins;

    public function __construct(private readonly PDO $connection)
    {
        $this->fields = [];
        $this->innerJoins = [];
    }

    public function setTablename(string $tablename): Read
    {
        $this->tablename = $tablename;

        return $this;
    }

    public function setCountField(string $countField): Read
    {
        $this->countField = $countField;

        return $this;
    }

    public function setFields(array $fields): Read
    {
        $this->fields = $fields;

        return $this;
    }

    public function setWhereClauses(string $whereClauses): Read
    {
        if ($whereClauses === 'id = :id') {
            $whereClauses = "{$this->tablename}.id = :id";
        }
        
        $this->whereClauses = $whereClauses;

        return $this;
    }

    public function setInnerJoin(InnerJoin $innerJoin): Read
    {
        array_push($this->innerJoins, $innerJoin);

        return $this;
    }

    public function run(array $values = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($this->getQuery());
            $stmt->execute($values);

            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function fetchOne(PDOStatement $stmt, string $entity)
    {
        $response = $stmt->fetchObject($entity);

        return $response;
    }

    public function fetchMany(PDOStatement $stmt, string $entity): array
    {
        return $stmt->fetchAll(PDO::FETCH_CLASS, $entity);
    }

    public function fetchCount(PDOStatement $stmt)
    {
        return intval($stmt->fetchObject()->total);
    }

    public function getQuery(): string
    {
        if (!isset($this->tablename)) {
            throw new AttributeClassNotFoundError('tablename', $this::class, self::class);
        }

        if (!isset($this->whereClauses)) {
            throw new AttributeClassNotFoundError('tablename', $this::class, self::class);
        }

        if (!empty($this->innerJoins) and empty($this->fields)) {
            throw new AttributeClassNotFoundError('fields', $this::class, self::class);
        }

        if (isset($this->countField)) {
            return "SELECT COUNT({$this->countField}) AS total FROM {$this->tablename} WHERE {$this->whereClauses}";
        }

        $joins = $this->getInnerJoins();

        return "\nSELECT {$this->getFields()} \nFROM {$this->tablename}{$joins}\nWHERE {$this->whereClauses}";
    }

    private function appendField(InnerJoin $join)
    {
        if (empty($this->fields)) {
            array_push($this->fields, "{$this->tablename}.*");
        }

        foreach ($join->fields as $field => $label) {
            Validations::mustBeForeignKey(['foreignKey' => $field], 'foreignKey');
            $this->fields[$field] = $label;
        }
    }

    private function getFields(): string
    {
        if (empty($this->fields)) {
            return '*';
        }

        $response = [];

        foreach ($this->fields as $key => $value) {
            if (is_string($key)) {
                array_push($response, "{$key} AS {$value}");
            } else {
                array_push($response, $value);
            }
        }

        return "\n\t" . implode(",\n\t", $response);
    }

    private function getInnerJoins(): string
    {
        if (empty($this->innerJoins)) {
            return '';
        }

        $joins = [];
        foreach ($this->innerJoins as $join) {
            $this->appendField($join);
            array_push($joins, $join);
        }

        return implode('', $joins);
    }
}
