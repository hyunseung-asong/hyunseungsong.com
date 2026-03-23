<?php
/**
 * JSON list of this company's users for remote peers (cURL / HTTP GET).
 * Response: { "company": "A", "users": [ { "name", "email", "joined", "plan" }, ... ] }
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/company_config.php';
require_once __DIR__ . '/../includes/user_repository.php';

try {
    $users = fetch_local_users();
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'users' => $users,
        ],
        JSON_UNESCAPED_UNICODE
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [
            'company' => COMPANY_ID,
            'users' => [],
            'error' => 'Could not load users from local database.',
        ],
        JSON_UNESCAPED_UNICODE
    );
}
