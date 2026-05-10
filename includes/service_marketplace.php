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

function marketplace_create_service_review(string $slug, string $user_email, int $rating, string $review_text): void {
    if (!service_by_slug($slug)) {
        throw new InvalidArgumentException('Service not found.');
    }
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Enter a valid user email.');
    }
    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException('Rating must be from 1 to 5.');
    }
    $review_text = trim($review_text);
    if ($review_text === '') {
        throw new InvalidArgumentException('Review text is required.');
    }

    $pdo = marketplace_pdo();
    $stmt = $pdo->prepare(
        'INSERT INTO service_reviews (company_id, service_slug, user_email, rating, review_text, created_at)
         VALUES (:company_id, :service_slug, :user_email, :rating, :review_text, NOW())'
    );
    $stmt->execute([
        ':company_id' => COMPANY_ID,
        ':service_slug' => $slug,
        ':user_email' => $user_email,
        ':rating' => $rating,
        ':review_text' => $review_text,
    ]);
}

function marketplace_fetch_service_reviews(?string $slug = null): array {
    $pdo = marketplace_pdo();
    $params = [];
    $sql = 'SELECT company_id, service_slug, user_email, rating, review_text,
                   DATE_FORMAT(created_at, \'%Y-%m-%d %H:%i:%s\') AS created_at
            FROM service_reviews';
    if ($slug !== null) {
        $sql .= ' WHERE service_slug = :service_slug';
        $params[':service_slug'] = $slug;
    }
    $sql .= ' ORDER BY created_at DESC, id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
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
        'SELECT s.service_slug,
                COALESCE(r.avg_rating, 0) AS avg_rating,
                COALESCE(r.review_count, 0) AS review_count,
                COALESCE(v.visit_count, 0) AS visit_count
         FROM (
             SELECT service_slug FROM service_reviews
             UNION
             SELECT service_slug FROM service_visits
         ) s
         LEFT JOIN (
             SELECT service_slug, AVG(rating) AS avg_rating, COUNT(*) AS review_count
             FROM service_reviews
             GROUP BY service_slug
         ) r ON r.service_slug = s.service_slug
         LEFT JOIN (
             SELECT service_slug, COUNT(*) AS visit_count
             FROM service_visits
             GROUP BY service_slug
         ) v ON v.service_slug = s.service_slug
         ORDER BY avg_rating DESC, review_count DESC, visit_count DESC, service_slug ASC'
    );
    $stats_by_slug = [];
    foreach ($stmt->fetchAll() as $row) {
        $stats_by_slug[$row['service_slug']] = $row;
    }

    $rows = [];
    foreach (service_catalog() as $slug => $svc) {
        $stats = $stats_by_slug[$slug] ?? [
            'avg_rating' => 0,
            'review_count' => 0,
            'visit_count' => 0,
        ];
        $rows[] = [
            'company' => COMPANY_ID,
            'service_slug' => $slug,
            'title' => $svc['title'],
            'short' => $svc['short'],
            'href' => $svc['href'],
            'image' => $svc['image'],
            'avg_rating' => round((float) $stats['avg_rating'], 2),
            'review_count' => (int) $stats['review_count'],
            'visit_count' => (int) $stats['visit_count'],
        ];
    }

    usort($rows, static function (array $a, array $b): int {
        return [$b['avg_rating'], $b['review_count'], $b['visit_count'], $a['service_slug']]
            <=> [$a['avg_rating'], $a['review_count'], $a['visit_count'], $b['service_slug']];
    });

    return $limit > 0 ? array_slice($rows, 0, $limit) : $rows;
}
