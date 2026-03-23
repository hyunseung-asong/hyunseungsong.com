<?php
/**
 * PDO access to local users table (this company's database only).
 */

/**
 * @return PDO
 */
function user_repository_pdo() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }
    $cfg = require __DIR__ . '/db_config.php';
    if (
        $cfg['host'] === 'YOUR_MYSQL_HOST'
        || $cfg['name'] === 'YOUR_DATABASE_NAME'
        || $cfg['user'] === 'YOUR_DATABASE_USER'
        || $cfg['pass'] === 'YOUR_DATABASE_PASSWORD'
    ) {
        throw new RuntimeException('Configure includes/db_config.php with real database credentials.');
    }
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $cfg['host'],
        $cfg['name']
    );
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}

/**
 * @return array<int, array{name: string, email: string, joined: string, plan: string}>
 */
function fetch_local_users() {
    $pdo = user_repository_pdo();
    $stmt = $pdo->query(
        'SELECT name, email, DATE_FORMAT(joined, \'%Y-%m-%d\') AS joined, plan FROM users ORDER BY id ASC'
    );
    return $stmt->fetchAll();
}
