<?php

$envFile = __DIR__ . '/../../.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = array_map('trim', explode('=', $line, 2));
            putenv("$key=$value");
        }
    }
} else {
    die('.env file not found.');
}
