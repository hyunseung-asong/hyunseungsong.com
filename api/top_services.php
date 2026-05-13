<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_marketplace.php';

try {
    $services = [];
    foreach (marketplace_fetch_service_stats(5) as $svc) {
        $services[] = [
            'title' => $svc['title'],
            'description' => $svc['short'],
            'price' => $svc['price'],
            'image_link' => '/' . ltrim($svc['image'], '/'),
            'product_link' => '/' . ltrim($svc['href'], '/'),
            'visit_count' => (int) $svc['visit_count'],
        ];
    }

    echo json_encode(
        $services,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
