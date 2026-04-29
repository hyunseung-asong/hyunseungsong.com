<?php
$page_title = 'Search Users';
require_once __DIR__ . '/includes/user_repository.php';

$query = trim((string) ($_GET['q'] ?? ''));
$users = [];
$error = null;

if ($query !== '') {
    try {
        $users = search_users($query);
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <p class="service-crumb"><a href="users.php">Users</a> / Search users</p>
                <h1>Search Users</h1>

                <form class="login-form user-search-form" method="get" action="user-search.php">
                    <div class="form-group">
                        <label for="q">Name, email, or phone number</label>
                        <input id="q" name="q" value="<?php echo htmlspecialchars($query); ?>" required>
                    </div>
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>

                <?php if ($error !== null): ?>
                <p class="login-error"><?php echo htmlspecialchars($error); ?></p>
                <?php elseif ($query !== ''): ?>
                    <?php if (empty($users)): ?>
                    <p class="no-contacts">No users matched your search.</p>
                    <?php else: ?>
                    <div class="secure-doc user-results">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Home address</th>
                                    <th>Home phone</th>
                                    <th>Cell phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['home_address']); ?></td>
                                    <td><?php echo htmlspecialchars($user['home_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['cell_phone']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
