<?php
/**
 * Fetch remote user list via cURL: JSON `{ company, users }` (e.g. api/company_users.php) or plain text (one name per line).
 *
 * @return array{ok: bool, data: ?array{company: string, users: array}, error: ?string}
 */
function fetch_remote_users($url) {
    if (!function_exists('curl_init')) {
        return [
            'ok' => false,
            'data' => null,
            'error' => 'PHP cURL extension is not available.',
        ];
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json, text/plain, */*',
        ],
    ]);

    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $curlErr = $errno ? curl_error($ch) : '';
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($errno) {
        return [
            'ok' => false,
            'data' => null,
            'error' => $curlErr !== '' ? $curlErr : 'cURL request failed.',
        ];
    }

    if ($httpCode >= 400) {
        return [
            'ok' => false,
            'data' => null,
            'error' => 'HTTP ' . $httpCode,
        ];
    }

    $raw = $body === false ? '' : $body;
    $decoded = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['users']) && is_array($decoded['users'])) {
        $company = isset($decoded['company']) && is_string($decoded['company']) && $decoded['company'] !== ''
            ? $decoded['company']
            : 'Remote';
        return [
            'ok' => true,
            'data' => [
                'company' => $company,
                'users' => $decoded['users'],
            ],
            'error' => null,
        ];
    }
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return [
            'ok' => false,
            'data' => null,
            'error' => 'JSON response did not include a users array.',
        ];
    }

    $lines = preg_split('/\r\n|\r|\n/', $raw);
    $users = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $users[] = [
                'name' => $line,
                'email' => '',
                'joined' => '',
                'plan' => '',
            ];
        }
    }
    if ($users === []) {
        return [
            'ok' => false,
            'data' => null,
            'error' => 'Response was not JSON with a users array or plain-text names.',
        ];
    }

    $host = parse_url($url, PHP_URL_HOST);
    $company = is_string($host) && $host !== '' ? $host : 'Remote';

    return [
        'ok' => true,
        'data' => [
            'company' => $company,
            'users' => $users,
        ],
        'error' => null,
    ];
}
