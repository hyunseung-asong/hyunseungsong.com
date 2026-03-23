<?php
/**
 * Secure section: document listing current users of the website.
 * Access requires administrator login (userid: admin).
 */
require_once __DIR__ . '/includes/auth.php';
auth_require_admin();

$page_title = 'Secure — Current Users';
require_once __DIR__ . '/includes/header.php';

// Document: current users of the website (sample data)
$site_users = [
    ['name' => 'Mary Smith',    'email' => 'mary.smith@example.com',  'joined' => '2025-01-15', 'plan' => 'Pro'],
    ['name' => 'John Wang',     'email' => 'john.wang@example.com',   'joined' => '2025-02-01', 'plan' => 'Starter'],
    ['name' => 'Alex Bington',  'email' => 'alex.bington@example.com', 'joined' => '2025-02-10', 'plan' => 'Pro'],
    ['name' => 'Jordan Lee',    'email' => 'jordan.lee@example.com',  'joined' => '2025-02-18', 'plan' => 'Starter'],
    ['name' => 'Sam Rivera',    'email' => 'sam.rivera@example.com', 'joined' => '2025-02-22', 'plan' => 'Team'],
];
?>

            <section class="content-section">
                <h1>Secure Section — Current Users</h1>
                <p>This document lists the current registered users of the RiftMind website. Access is restricted to administrators.</p>
                <p class="secure-logout"><a href="logout.php" class="btn btn-secondary">Log out</a></p>
                <div class="secure-doc">
                    <h2>Registered Users (as of <?php echo date('F j, Y'); ?>)</h2>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Plan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($site_users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['name']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['joined']); ?></td>
                                <td><?php echo htmlspecialchars($u['plan']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
