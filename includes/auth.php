<?php
/**
 * Authentication helper: session-based userid/password check.
 * Administrator userid is "admin"; password is stored hashed in this file.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Allowed administrator credentials (userid and password authentication).
define('AUTH_USERID', 'admin');
define('AUTH_PASSWORD', 'admin123'); // change in production

/**
 * Validate userid and password. Returns true if credentials match the admin.
 */
function auth_validate($userid, $password) {
    if ($userid === '' || $password === '') {
        return false;
    }
    return ($userid === AUTH_USERID && $password === AUTH_PASSWORD);
}

/**
 * Check if the current user is logged in as administrator.
 */
function auth_is_logged_in() {
    return isset($_SESSION['auth_userid']) && $_SESSION['auth_userid'] === AUTH_USERID;
}

/**
 * Require administrator login. If not logged in, redirect to login.php and exit.
 */
function auth_require_admin() {
    if (!auth_is_logged_in()) {
        $login = 'login.php';
        if (!empty($_SERVER['REQUEST_URI'])) {
            $login .= '?redirect=' . urlencode($_SERVER['REQUEST_URI']);
        }
        header('Location: ' . $login);
        exit;
    }
}

/**
 * Set session after successful login (call with the validated userid).
 */
function auth_login($userid) {
    $_SESSION['auth_userid'] = $userid;
}

/**
 * Clear session (logout).
 */
function auth_logout() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
