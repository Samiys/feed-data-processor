<?php
return [
    'db' => [
        'default' => getenv('DB_TYPE') ?: 'mysql',
        'connections' => [
            'mysql' => [
                'host' => getenv('MYSQL_DB_HOST') ?: 'mysql',
                'dbname' => getenv('MYSQL_DB_NAME') ?: 'feed_data',
                'user' => getenv('MYSQL_DB_USER') ?: 'root',
                'password' => getenv('MYSQL_DB_PASSWORD') ?: 'secret',
                'port' => getenv('MYSQL_DB_PORT') ?: 3306,
                'charset' => 'utf8mb4',
            ],
            'pgsql' => [
                'host' => getenv('PGSQL_DB_HOST') ?: 'postgres',
                'dbname' => getenv('PGSQL_DB_NAME') ?: 'feed_data',
                'user' => getenv('PGSQL_DB_USER') ?: 'postgres',
                'password' => getenv('PGSQL_DB_PASSWORD') ?: 'secret',
                'port' => getenv('PGSQL_DB_PORT') ?: 5432,
                'charset' => 'utf8',
            ],
        ],
    ],
];
?>
