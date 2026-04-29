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
        'SELECT CONCAT(first_name, \' \', last_name) AS name, email, DATE_FORMAT(joined, \'%Y-%m-%d\') AS joined, plan FROM users ORDER BY id ASC'
    );
    return $stmt->fetchAll();
}

/**
 * @param array{first_name: string, last_name: string, email: string, home_address: string, home_phone: string, cell_phone: string} $user
 */
function create_user(array $user) {
    $pdo = user_repository_pdo();
    $stmt = $pdo->prepare(
        'INSERT INTO users (first_name, last_name, email, home_address, home_phone, cell_phone, joined, plan)
         VALUES (:first_name, :last_name, :email, :home_address, :home_phone, :cell_phone, CURDATE(), :plan)'
    );
    $stmt->execute([
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':email' => $user['email'],
        ':home_address' => $user['home_address'],
        ':home_phone' => $user['home_phone'],
        ':cell_phone' => $user['cell_phone'],
        ':plan' => isset($user['plan']) && $user['plan'] !== '' ? $user['plan'] : 'Starter',
    ]);
}

/**
 * @return array<int, array{first_name: string, last_name: string, email: string, home_address: string, home_phone: string, cell_phone: string, joined: string, plan: string}>
 */
function find_users(string $term = '', string $sort = 'last_name', string $direction = 'asc') {
    $pdo = user_repository_pdo();
    $term = trim($term);

    $sort_columns = [
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'email' => 'email',
        'home_address' => 'home_address',
        'home_phone' => 'home_phone',
        'cell_phone' => 'cell_phone',
        'joined' => 'joined',
        'plan' => 'plan',
    ];
    $sort_sql = $sort_columns[$sort] ?? $sort_columns['last_name'];
    $direction_sql = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';

    $sql = 'SELECT first_name, last_name, email, home_address, home_phone, cell_phone, DATE_FORMAT(joined, \'%Y-%m-%d\') AS joined, plan
            FROM users';
    $params = [];
    if ($term !== '') {
        $sql .= ' WHERE first_name LIKE :term
               OR last_name LIKE :term
               OR CONCAT(first_name, \' \', last_name) LIKE :term
               OR email LIKE :term
               OR home_address LIKE :term
               OR home_phone LIKE :term
               OR cell_phone LIKE :term';
        $params[':term'] = '%' . $term . '%';
    }
    $sql .= sprintf(' ORDER BY %s %s, id ASC', $sort_sql, $direction_sql);

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * @return array<int, array{first_name: string, last_name: string, email: string, home_address: string, home_phone: string, cell_phone: string, joined: string, plan: string}>
 */
function search_users(string $term) {
    return find_users($term);
}
