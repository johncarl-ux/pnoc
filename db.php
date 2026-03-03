<?php

declare(strict_types=1);

function getDbConnection(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    $host = '127.0.0.1';
    $username = 'root';
    $password = '';
    $database = 'pnoc_db';

    $connection = @new mysqli($host, $username, $password, $database);

    if ($connection->connect_errno) {
        throw new RuntimeException('Database connection failed: ' . $connection->connect_error);
    }

    if (!$connection->set_charset('utf8mb4')) {
        throw new RuntimeException('Failed to set UTF-8 charset: ' . $connection->error);
    }

    return $connection;
}
