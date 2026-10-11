<?php
// config/database.php
// Reads credentials from outside the web folder and returns a PDO connection.

// Store times in UTC; convert to Pakistan time only when displaying.
date_default_timezone_set('UTC');
define('APP_TIMEZONE', 'Asia/Karachi');

define('SECRETS_FILE', '/home/faizan-asif/noticediff-secrets/db.env');

function loadEnv($path) {
    if (!is_readable($path)) {
        die('Configuration file is missing or not readable.');
    }
    $values = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        // Everything after the FIRST "=" is the value, so "@" or "#" are safe.
        $values[trim(substr($line, 0, $pos))] = substr($line, $pos + 1);
    }
    return $values;
}

function getDB() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }
    $env = loadEnv(SECRETS_FILE);
    $dsn = 'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_NAME'] . ';charset=utf8mb4';
    $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec("SET time_zone = '+00:00'");
    return $pdo;
}
