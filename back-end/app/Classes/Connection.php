<?php

namespace App\Classes;

use App\Interfaces\Connection as ConnectionInterface;
use PDO;

class Connection implements ConnectionInterface
{
    private string $database,
        $host = '127.0.0.1',
        $username = 'root';

    private string|null $password;

    private int $port = 3306;

    private PDO $pdo;

    public function setDatabase(string $database): static
    {
        $this->database = $database;

        return $this;
    }

    public function setHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function setCredential(string $username, string|null $password): static
    {
        $this->username = $username;

        $this->password = $password;

        return $this;
    }

    public function getDsn(): string
    {
        return sprintf('mysql:dbname=%s;host=%s;port=%d', $this->database, $this->host, $this->port);
    }

    public function newConnection(): static
    {
        $this->pdo = new PDO(
            $this->getDsn(),
            $this->username,
            $this->password
        );

        return $this;
    }

    public function singleton(): PDO
    {
        if (!isset($this->pdo)) {
            $this->newConnection();
        }

        return $this->pdo;
    }
}
