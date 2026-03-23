<?php
$page_title = 'Contacts';
/**
 * Load contacts from data/contacts.txt.
 * Format: blocks separated by "---", each line "Key: value".
 * When migrating to MySQL, replace this with a DB query and keep the same $contacts array structure.
 */
$contacts_file = __DIR__ . '/data/contacts.txt';
$contacts = [];
if (file_exists($contacts_file)) {
    $raw = file_get_contents($contacts_file);
    $raw = preg_replace('/\r\n?/', "\n", $raw);
    $blocks = array_filter(array_map('trim', explode('---', $raw)));
    foreach ($blocks as $block) {
        $contact = [];
        foreach (explode("\n", $block) as $line) {
            $line = trim($line);
            if (preg_match('/^(\w+):\s*(.+)$/', $line, $m)) {
                $contact[$m[1]] = trim($m[2]);
            }
        }
        if (!empty($contact)) {
            $contacts[] = $contact;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <h1>Contacts</h1>
                <p>Get in touch with the RiftMind team. We’re here for sales, support, and technical questions.</p>
                <?php if (empty($contacts)): ?>
                <p class="no-contacts">No contacts are currently available. Please add entries to <code>data/contacts.txt</code>.</p>
                <?php else: ?>
                <div class="contacts-list">
                    <?php foreach ($contacts as $c): ?>
                    <div class="contact-card">
                        <h2><?php echo htmlspecialchars($c['Name'] ?? '—'); ?></h2>
                        <p class="role"><?php echo htmlspecialchars($c['Role'] ?? ''); ?></p>
                        <p><a href="mailto:<?php echo htmlspecialchars($c['Email'] ?? ''); ?>"><?php echo htmlspecialchars($c['Email'] ?? '—'); ?></a></p>
                        <p><?php echo htmlspecialchars($c['Phone'] ?? '—'); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
