<?php
$page_title = 'Search Users';
require_once __DIR__ . '/includes/user_repository.php';

$query = trim((string) ($_GET['q'] ?? ''));
$sort = (string) ($_GET['sort'] ?? 'last_name');
$direction = strtolower((string) ($_GET['direction'] ?? 'asc'));
$users = [];
$error = null;

try {
    $users = find_users($query, $sort, $direction);
} catch (Throwable $e) {
    $error = $e->getMessage();
}

$columns = [
    'first_name' => 'First name',
    'last_name' => 'Last name',
    'email' => 'Email',
    'home_address' => 'Home address',
    'home_phone' => 'Home phone',
    'cell_phone' => 'Cell phone',
    'joined' => 'Joined',
    'plan' => 'Plan',
];
$active_sort = array_key_exists($sort, $columns) ? $sort : 'last_name';
$active_direction = $direction === 'desc' ? 'desc' : 'asc';
$sort_url = static function (string $column) use ($query, $active_sort, $active_direction): string {
    $next_direction = $active_sort === $column && $active_direction === 'asc' ? 'desc' : 'asc';
    return 'user-search.php?' . http_build_query([
        'q' => $query,
        'sort' => $column,
        'direction' => $next_direction,
    ]);
};
$sort_label = static function (string $column, string $label) use ($active_sort, $active_direction): string {
    if ($active_sort !== $column) {
        return $label;
    }
    return $label . ($active_direction === 'asc' ? ' ▲' : ' ▼');
};

require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <p class="service-crumb"><a href="users.php">Users</a> / Search users</p>
                <h1>Search Users</h1>

                <form class="login-form user-search-form" method="get" action="user-search.php">
                    <div class="form-group">
                        <label for="q">Name, email, address, or phone number</label>
                        <input id="q" name="q" value="<?php echo htmlspecialchars($query); ?>">
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($active_sort); ?>">
                        <input type="hidden" name="direction" value="<?php echo htmlspecialchars($active_direction); ?>">
                    </div>
                    <button class="btn btn-primary" type="submit">Search</button>
                    <?php if ($query !== ''): ?>
                    <a class="btn btn-secondary" href="user-search.php?sort=<?php echo urlencode($active_sort); ?>&direction=<?php echo urlencode($active_direction); ?>">Clear</a>
                    <?php endif; ?>
                </form>

                <?php if ($error !== null): ?>
                <p class="login-error"><?php echo htmlspecialchars($error); ?></p>
                <?php else: ?>
                    <?php if (empty($users)): ?>
                    <p class="no-contacts">No users matched your search.</p>
                    <?php else: ?>
                    <p class="results-count"><?php echo count($users); ?> user<?php echo count($users) === 1 ? '' : 's'; ?> shown.</p>
                    <div class="secure-doc user-results">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <?php foreach ($columns as $column => $label): ?>
                                    <th><a href="<?php echo htmlspecialchars($sort_url($column)); ?>"><?php echo htmlspecialchars($sort_label($column, $label)); ?></a></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['home_address']); ?></td>
                                    <td><?php echo htmlspecialchars($user['home_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['cell_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['joined']); ?></td>
                                    <td><?php echo htmlspecialchars($user['plan']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
