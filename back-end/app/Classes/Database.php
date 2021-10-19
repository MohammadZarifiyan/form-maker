<?php

namespace App\Classes;

use App\Interfaces\Connection as ConnectionInterface;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public string $sql;

    public array|null $parameters, $values;

    public ConnectionInterface $connection;

    public function setConnection(ConnectionInterface $connection): static
    {
        $this->connection = $connection;

        return $this;
    }

    public function setSql(string $sql): static
    {
        $this->sql = $sql;

        return $this;
    }

    public function setParameters(array|null $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setValues(array|null $values): static
    {
        $this->values = $values;

        return $this;
    }

    public function prepareSql(): false|PDOStatement
    {
        return $this->connection->singleton()->prepare($this->sql);
    }

    public function bindValues(PDOStatement $sth)
    {
        foreach ($this->parameters as $parameter) {
            if (!isset($this->values[$parameter['key']])) {
                throw new PDOException(sprintf('%s value in not set.', $parameter['key']));
            }

            $value = $this->values[$parameter['key']];

            $result = $sth->bindValue(
                ':' . $parameter['key'],
                $value,
                $this->getPdoType($parameter['type'])
            );

            if (!$result) {
                throw new PDOException(sprintf('Cannot assign % to %s.', $value, $parameter['key']));
            }
        }
    }

    public function getPdoType(string $type): int
    {
        return match($type) {
            'integer' => PDO::PARAM_INT,
            'string' => PDO::PARAM_STR,
            'boolean' => PDO::PARAM_BOOL,
        };
    }

    public function run(): bool|PDOStatement
    {
        $sth = $this->prepareSql();

        $this->parameters && $this->bindValues($sth);

        $sth->execute();

        return $sth;
    }
}
