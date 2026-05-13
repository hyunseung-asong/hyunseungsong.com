<?php
require_once __DIR__ . '/company_config.php';
require_once __DIR__ . '/service_catalog.php';
require_once __DIR__ . '/user_repository.php';

define('MARKETPLACE_USER_EMAIL_COOKIE', 'riftmind_marketplace_user_email');

function marketplace_pdo(): PDO {
    return user_repository_pdo();
}

function marketplace_cookie_path(): string {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (preg_match('#/services$#', $dir) || preg_match('#/api$#', $dir)) {
        $dir = dirname($dir);
    }
    if ($dir === '/' || $dir === '\\' || $dir === '.') {
        return '/';
    }
    return rtrim($dir, '/') . '/';
}

function marketplace_remember_user_email(string $email): string {
    $email = trim($email);
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '';
    }

    setcookie(
        MARKETPLACE_USER_EMAIL_COOKIE,
        $email,
        [
            'expires' => time() + (60 * 60 * 24 * 30),
            'path' => marketplace_cookie_path(),
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => false,
            'samesite' => 'Lax',
        ]
    );
    $_COOKIE[MARKETPLACE_USER_EMAIL_COOKIE] = $email;

    return $email;
}

function marketplace_current_user_email(): string {
    $candidate = trim((string) ($_POST['user_email'] ?? $_GET['user_email'] ?? ''));
    if ($candidate === '') {
        $candidate = trim((string) ($_COOKIE[MARKETPLACE_USER_EMAIL_COOKIE] ?? ''));
    }

    return marketplace_remember_user_email($candidate);
}

function marketplace_record_service_visit(string $slug, string $user_email = ''): void {
    if (!service_by_slug($slug)) {
        return;
    }

    $pdo = marketplace_pdo();
    $stmt = $pdo->prepare(
        'INSERT INTO service_visits (company_id, service_slug, user_email, visited_at)
         VALUES (:company_id, :service_slug, :user_email, NOW())'
    );
    $stmt->execute([
        ':company_id' => COMPANY_ID,
        ':service_slug' => $slug,
        ':user_email' => $user_email !== '' ? $user_email : null,
    ]);
}

function marketplace_fetch_service_visits(?string $user_email = null): array {
    $pdo = marketplace_pdo();
    $params = [];
    $sql = 'SELECT company_id, service_slug, user_email,
                   DATE_FORMAT(visited_at, \'%Y-%m-%d %H:%i:%s\') AS visited_at
            FROM service_visits';
    if ($user_email !== null && $user_email !== '') {
        $sql .= ' WHERE user_email = :user_email';
        $params[':user_email'] = $user_email;
    }
    $sql .= ' ORDER BY visited_at DESC, id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function marketplace_fetch_service_stats(int $limit = 0): array {
    $pdo = marketplace_pdo();
    $stmt = $pdo->query(
        'SELECT service_slug, COUNT(*) AS visit_count
         FROM service_visits
         GROUP BY service_slug
         ORDER BY visit_count DESC, service_slug ASC'
    );
    $stats_by_slug = [];
    foreach ($stmt->fetchAll() as $row) {
        $stats_by_slug[$row['service_slug']] = $row;
    }

    $rows = [];
    foreach (service_catalog() as $slug => $svc) {
        $stats = $stats_by_slug[$slug] ?? [
            'visit_count' => 0,
        ];
        $rows[] = [
            'company' => COMPANY_ID,
            'service_slug' => $slug,
            'title' => $svc['title'],
            'short' => $svc['short'],
            'price' => $svc['price'],
            'href' => $svc['href'],
            'image' => $svc['image'],
            'visit_count' => (int) $stats['visit_count'],
        ];
    }

    usort($rows, static function (array $a, array $b): int {
        return [$b['visit_count'], $a['service_slug']]
            <=> [$a['visit_count'], $b['service_slug']];
    });

    return $limit > 0 ? array_slice($rows, 0, $limit) : $rows;
}
