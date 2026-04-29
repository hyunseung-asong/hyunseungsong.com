<?php
$page_title = 'Create User';
require_once __DIR__ . '/includes/user_repository.php';

$fields = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'home_address' => '',
    'home_phone' => '',
    'cell_phone' => '',
];
$errors = [];
$created = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $key => $value) {
        $fields[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    foreach ($fields as $key => $value) {
        if ($value === '') {
            $errors[$key] = 'This field is required.';
        }
    }

    if ($fields['email'] !== '' && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (empty($errors)) {
        try {
            create_user($fields);
            $created = true;
            foreach ($fields as $key => $value) {
                $fields[$key] = '';
            }
        } catch (Throwable $e) {
            $errors['database'] = $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <p class="service-crumb"><a href="users.php">Users</a> / Create user</p>
                <h1>Create User</h1>

                <?php if ($created): ?>
                <p class="form-success">User created successfully.</p>
                <?php endif; ?>
                <?php if (isset($errors['database'])): ?>
                <p class="login-error"><?php echo htmlspecialchars($errors['database']); ?></p>
                <?php endif; ?>

                <form class="login-form user-form" method="post" action="user-create.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">First name</label>
                            <input id="first_name" name="first_name" value="<?php echo htmlspecialchars($fields['first_name']); ?>" required>
                            <?php if (isset($errors['first_name'])): ?><small><?php echo htmlspecialchars($errors['first_name']); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input id="last_name" name="last_name" value="<?php echo htmlspecialchars($fields['last_name']); ?>" required>
                            <?php if (isset($errors['last_name'])): ?><small><?php echo htmlspecialchars($errors['last_name']); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($fields['email']); ?>" required>
                            <?php if (isset($errors['email'])): ?><small><?php echo htmlspecialchars($errors['email']); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="home_phone">Home phone</label>
                            <input id="home_phone" name="home_phone" type="tel" value="<?php echo htmlspecialchars($fields['home_phone']); ?>" required>
                            <?php if (isset($errors['home_phone'])): ?><small><?php echo htmlspecialchars($errors['home_phone']); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="cell_phone">Cell phone</label>
                            <input id="cell_phone" name="cell_phone" type="tel" value="<?php echo htmlspecialchars($fields['cell_phone']); ?>" required>
                            <?php if (isset($errors['cell_phone'])): ?><small><?php echo htmlspecialchars($errors['cell_phone']); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group form-group-wide">
                            <label for="home_address">Home address</label>
                            <input id="home_address" name="home_address" value="<?php echo htmlspecialchars($fields['home_address']); ?>" required>
                            <?php if (isset($errors['home_address'])): ?><small><?php echo htmlspecialchars($errors['home_address']); ?></small><?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Create user</button>
                </form>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
