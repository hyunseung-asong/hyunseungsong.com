<?php
/**
 * Combined view: local users (this company's DB) + remote companies via cURL.
 */
$page_title = 'All Companies’ Users TEST';
require_once __DIR__ . '/includes/company_config.php';
require_once __DIR__ . '/includes/user_repository.php';
require_once __DIR__ . '/includes/curl_fetch_json.php';
require_once __DIR__ . '/includes/header.php';

$local_users = null;
$local_error = null;
try {
    $local_users = fetch_local_users();
} catch (Throwable $e) {
    $local_error = $e->getMessage();
}

$remote_sections = [];
foreach (get_remote_user_api_urls() as $remote_url) {
    $result = fetch_remote_users($remote_url);
    if (!$result['ok']) {
        $remote_sections[] = [
            'url' => $remote_url,
            'label' => 'Remote',
            'users' => [],
            'error' => $result['error'],
        ];
        continue;
    }
    $data = $result['data'];
    $companyLabel = isset($data['company']) && is_string($data['company']) && $data['company'] !== ''
        ? $data['company']
        : 'Remote';
    $users = isset($data['users']) && is_array($data['users']) ? $data['users'] : [];
    $remote_sections[] = [
        'url' => $remote_url,
        'label' => $companyLabel,
        'users' => $users,
        'error' => null,
    ];
}
?>

            <section class="content-section">
                <h1>All Companies’ Users</h1>
                <p>SETUP AUTO DEPLOY 2. Users from this site’s database appear below under <strong>Company <?php echo htmlspecialchars(COMPANY_ID); ?></strong>. Other sites are loaded with cURL: each URL may return JSON (<code>company</code> + <code>users</code>) or plain text with one name per line.</p>

                <div class="secure-doc">
                    <h2>Company <?php echo htmlspecialchars(COMPANY_ID); ?> (local database)</h2>
                    <?php if ($local_error !== null): ?>
                    <p class="login-error"><?php echo htmlspecialchars($local_error); ?></p>
                    <?php else: ?>
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
                            <?php foreach ($local_users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['name']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['joined']); ?></td>
                                <td><?php echo htmlspecialchars($u['plan']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>

                <?php foreach ($remote_sections as $section): ?>
                <div class="secure-doc">
                    <h2>Company <?php echo htmlspecialchars($section['label']); ?> <span class="remote-api-url">(<?php echo htmlspecialchars($section['url']); ?>)</span></h2>
                    <?php if ($section['error'] !== null): ?>
                    <p class="login-error"><?php echo htmlspecialchars($section['error']); ?></p>
                    <?php elseif (empty($section['users'])): ?>
                    <p>No users returned.</p>
                    <?php else: ?>
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
                            <?php foreach ($section['users'] as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(isset($u['name']) ? (string) $u['name'] : ''); ?></td>
                                <td><?php echo htmlspecialchars(isset($u['email']) ? (string) $u['email'] : ''); ?></td>
                                <td><?php echo htmlspecialchars(isset($u['joined']) ? (string) $u['joined'] : ''); ?></td>
                                <td><?php echo htmlspecialchars(isset($u['plan']) ? (string) $u['plan'] : ''); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
