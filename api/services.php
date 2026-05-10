<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_catalog.php';

$services = [];
foreach (service_catalog() as $slug => $svc) {
    $services[] = [
        'company' => COMPANY_ID,
        'service_slug' => $slug,
        'title' => $svc['title'],
        'short' => $svc['short'],
        'href' => $svc['href'],
        'image' => $svc['image'],
    ];
}

echo json_encode(
    [
        'company' => COMPANY_ID,
        'services' => $services,
    ],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
