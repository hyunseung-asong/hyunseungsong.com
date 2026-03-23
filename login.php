<?php
/**
 * Login page: userid and password authentication.
 * On success, redirects to redirect param or secure.php.
 */
require_once __DIR__ . '/includes/auth.php';

$error = '';
$userid = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = isset($_POST['userid']) ? trim($_POST['userid']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (auth_validate($userid, $password)) {
        auth_login($userid);
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'secure.php';
        $redirect = preg_replace('/[^a-z0-9_\.\-]/i', '', $redirect) ?: 'secure.php';
        if (strpos($redirect, 'logout') !== false) {
            $redirect = 'secure.php';
        }
        header('Location: ' . $redirect);
        exit;
    }

    $error = 'Invalid userid or password. Please try again.';
}

$page_title = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <h1>Administrator Login</h1>
                <p>Enter your userid and password to access the secure section.</p>
                <?php if ($error): ?>
                <p class="login-error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="post" action="login.php" class="login-form">
                    <div class="form-group">
                        <label for="userid">User ID</label>
                        <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($userid); ?>" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Log in</button>
                </form>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
