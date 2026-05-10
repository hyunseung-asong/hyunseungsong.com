<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_marketplace.php';

try {
    $services = marketplace_fetch_service_stats(5);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'ranking' => 'avg_rating_desc_review_count_desc_visit_count_desc',
            'top_services' => $services,
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'ranking' => 'avg_rating_desc_review_count_desc_visit_count_desc',
            'top_services' => [],
            'error' => 'Could not load top services.',
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
