<?php

namespace App\Classes;

class ParameterKeyValidator
{
    private string $sql;

    public function setSql(string $sql): static
    {
        $this->sql = $sql;

        return $this;
    }

    public function getSqlArguments(): array
    {
        if (!preg_match_all('/:(?!\d|_)([\w\d]+)/', $this->sql, $arguments)) {
            return [];
        }

        return $arguments[1];
    }

    public function verifyParameterKey(string $parameterKey): bool
    {
        if ($query_arguments = $this->getSqlArguments()) {
            return in_array($parameterKey, $query_arguments);
        }

        return false;
    }
}
