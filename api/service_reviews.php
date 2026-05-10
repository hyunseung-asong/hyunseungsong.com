<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_marketplace.php';

$slug = trim((string) ($_GET['service_slug'] ?? ''));

try {
    $reviews = marketplace_fetch_service_reviews($slug !== '' ? $slug : null);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'reviews' => $reviews,
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'reviews' => [],
            'error' => 'Could not load service reviews.',
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
