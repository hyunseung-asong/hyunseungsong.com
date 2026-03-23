<?php
$page_title = 'News';
$news_file = __DIR__ . '/data/news.txt';
$news_items = [];
if (file_exists($news_file)) {
    $raw = file_get_contents($news_file);
    $raw = preg_replace('/\r\n?/', "\n", $raw);
    $blocks = array_filter(array_map('trim', explode('---', $raw)));
    foreach ($blocks as $block) {
        $item = [];
        foreach (explode("\n", $block) as $line) {
            if (preg_match('/^(\w+):\s*(.+)$/', trim($line), $m)) {
                $item[$m[1]] = trim($m[2]);
            }
        }
        if (!empty($item)) {
            $news_items[] = $item;
        }
    }
}
if (empty($news_items)) {
    $news_items = [
        ['Date' => '2025-02-20', 'Title' => 'RiftMind beta is live', 'Body' => 'We’re excited to open beta access for real-time AI coaching. Sign up via Contacts to get early access.'],
        ['Date' => '2025-02-15', 'Title' => 'New Pro plan with VOD review', 'Body' => 'Pro subscribers now get AI-powered VOD review and session summaries.'],
        ['Date' => '2025-02-01', 'Title' => 'Welcome to RiftMind', 'Body' => 'RiftMind launches: real-time AI coaching for League of Legends. Climb smarter with live feedback.'],
    ];
}
require_once __DIR__ . '/includes/header.php';
?>

            <section class="content-section">
                <h1>News</h1>
                <p>Latest updates about RiftMind, our products, and the service.</p>
                <div class="news-list">
                    <?php foreach ($news_items as $item): ?>
                    <article class="news-item">
                        <time datetime="<?php echo htmlspecialchars($item['Date'] ?? ''); ?>"><?php echo htmlspecialchars($item['Date'] ?? ''); ?></time>
                        <h2><?php echo htmlspecialchars($item['Title'] ?? ''); ?></h2>
                        <p><?php echo htmlspecialchars($item['Body'] ?? ''); ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
