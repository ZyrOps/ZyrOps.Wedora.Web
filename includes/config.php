<?php
declare(strict_types=1);

define('WEDORA_ROOT', dirname(__DIR__));
define('WEDORA_PUBLIC', WEDORA_ROOT . DIRECTORY_SEPARATOR . 'public');

function wedora_load_env(): void
{
    $path = WEDORA_ROOT . DIRECTORY_SEPARATOR . '.env';
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

function wedora_env(string $key, mixed $default = null): mixed
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    return $_ENV[$key] ?? $default;
}

function wedora_boot_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    session_name('wedora_session');
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 45,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function wedora_db(): ?PDO
{
    static $pdo = false;
    if ($pdo !== false) {
        return $pdo;
    }

    $dsn = wedora_env('DB_DSN');
    if (!$dsn) {
        $host = wedora_env('DB_HOST');
        $name = wedora_env('DB_NAME');
        if ($host && $name) {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8mb4';
        }
    }

    if (!$dsn) {
        $pdo = null;
        return null;
    }

    try {
        $pdo = new PDO((string) $dsn, (string) wedora_env('DB_USER', ''), (string) wedora_env('DB_PASS', ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable) {
        $pdo = null;
    }

    return $pdo;
}

wedora_load_env();
wedora_boot_session();
