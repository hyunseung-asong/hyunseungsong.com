<?php
require_once __DIR__ . '/service_catalog.php';

define('RECENT_SERVICES_COOKIE', 'riftmind_recent_services');
define('RECENT_SERVICES_MAX', 5);
define('RECENT_SERVICES_TTL', 60 * 60 * 24 * 30);

/**
 * Web path prefix for cookies (e.g. / or /CMPE272/) so the cookie is scoped to this site folder.
 */
function recent_services_cookie_path(): string {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (preg_match('#/services$#', $dir)) {
        $dir = dirname($dir);
    }
    if ($dir === '/' || $dir === '\\' || $dir === '.') {
        return '/';
    }
    return rtrim($dir, '/') . '/';
}

/**
 * Read last visited services from the browser cookie (newest first).
 */
function recent_services_read(): array {
    if (empty($_COOKIE[RECENT_SERVICES_COOKIE])) {
        return [];
    }
    $raw = $_COOKIE[RECENT_SERVICES_COOKIE];
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }
    $out = [];
    foreach ($data as $row) {
        if (!is_array($row)) {
            continue;
        }
        $slug = isset($row['slug']) ? (string) $row['slug'] : '';
        if ($slug === '') {
            continue;
        }
        $svc = service_by_slug($slug);
        if (!$svc) {
            continue;
        }
        $out[] = [
            'slug' => $slug,
            'title' => $svc['title'],
            'href' => $svc['href'],
            'image' => $svc['image'],
            'visited_at' => isset($row['visited_at']) ? (int) $row['visited_at'] : 0,
        ];
    }
    return $out;
}

/**
 * Record a service page visit in a cookie (keeps the last 5 unique services, newest first).
 */
function service_record_visit(string $slug): void {
    $svc = service_by_slug($slug);
    if (!$svc) {
        return;
    }

    $existing = [];
    if (!empty($_COOKIE[RECENT_SERVICES_COOKIE])) {
        $decoded = json_decode($_COOKIE[RECENT_SERVICES_COOKIE], true);
        if (is_array($decoded)) {
            $existing = $decoded;
        }
    }

    $filtered = [];
    foreach ($existing as $row) {
        if (is_array($row) && isset($row['slug']) && $row['slug'] !== $slug) {
            $filtered[] = $row;
        }
    }

    array_unshift($filtered, [
        'slug' => $slug,
        'visited_at' => time(),
    ]);

    $filtered = array_slice($filtered, 0, RECENT_SERVICES_MAX);

    $payload = json_encode($filtered, JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        return;
    }

    setcookie(
        RECENT_SERVICES_COOKIE,
        $payload,
        [
            'expires' => time() + RECENT_SERVICES_TTL,
            'path' => recent_services_cookie_path(),
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => false,
            'samesite' => 'Lax',
        ]
    );

    $_COOKIE[RECENT_SERVICES_COOKIE] = $payload;
}
