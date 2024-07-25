<?php

namespace App\Database;

class DatabaseDTO {
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function getMySQLConfig(): array {
        return $this->config['db']['connections']['mysql'];
    }

    public function getPgSQLConfig(): array {
        return $this->config['db']['connections']['pgsql'];
    }
}
