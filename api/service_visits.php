<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_marketplace.php';

$user_email = trim((string) ($_GET['user_email'] ?? ''));

if ($user_email !== '' && !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'visits' => [],
            'error' => 'Invalid user_email filter.',
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

try {
    $visits = marketplace_fetch_service_visits($user_email);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'visits' => $visits,
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'visits' => [],
            'error' => 'Could not load service visits.',
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
