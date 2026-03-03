<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getDbConnection(): mysqli
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'pnoc_db';

    $connection = new mysqli($host, $username, $password, $database);
    $connection->set_charset('utf8mb4');

    return $connection;
}
