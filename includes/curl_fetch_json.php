<?php
/**
 * Fetch remote JSON (user list API) via PHP cURL extension.
 *
 * @return array{ok: bool, data: ?array, error: ?string}
 */
function fetch_remote_users_json($url) {
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
            'Accept: application/json',
        ],
    ]);

    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $curlErr = $errno ? curl_error($ch) : '';
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

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

    $decoded = json_decode($body === false ? '' : $body, true);
    if (!is_array($decoded)) {
        return [
            'ok' => false,
            'data' => null,
            'error' => 'Response was not valid JSON.',
        ];
    }

    return [
        'ok' => true,
        'data' => $decoded,
        'error' => null,
    ];
}
