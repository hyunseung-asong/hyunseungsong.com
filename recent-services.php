<?php
require_once __DIR__ . '/includes/recent_services.php';

$page_title = 'Recently viewed services';
require_once __DIR__ . '/includes/header.php';

$recent = recent_services_read();
?>

            <section class="content-section">
                <p class="service-crumb"><a href="products.php">Services</a></p>
                <h1>Last 5 services you viewed</h1>
                <p>These are tracked in your browser with a cookie (<code><?php echo htmlspecialchars(RECENT_SERVICES_COOKIE); ?></code>) each time you open a service page.</p>
                <?php if (empty($recent)): ?>
                <p class="no-contacts">You have not visited any service pages yet. Browse the <a href="products.php">Services</a> catalog and open a few pages—then come back here.</p>
                <?php else: ?>
                <div class="recent-services-grid">
                    <?php foreach ($recent as $row): ?>
                    <article class="recent-service-card">
                        <a class="recent-service-link" href="<?php echo htmlspecialchars($row['href']); ?>">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" width="480" height="270" alt="">
                            <span class="recent-service-title"><?php echo htmlspecialchars($row['title']); ?></span>
                        </a>
                        <?php if (!empty($row['visited_at'])): ?>
                        <time class="recent-service-time" datetime="<?php echo date('c', $row['visited_at']); ?>">Viewed <?php echo htmlspecialchars(date('M j, Y g:i A', $row['visited_at'])); ?></time>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
