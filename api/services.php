<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/service_catalog.php';

$services = [];
foreach (service_catalog() as $slug => $svc) {
    $services[] = [
        'title' => $svc['title'],
        'description' => $svc['short'],
        'price' => $svc['price'],
        'image_link' => '/' . ltrim($svc['image'], '/'),
        'product_link' => '/' . ltrim($svc['href'], '/'),
    ];
}

echo json_encode(
    $services,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
