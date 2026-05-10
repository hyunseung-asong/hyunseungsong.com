<?php
$page_title = 'Top 5 Services';
require_once __DIR__ . '/includes/service_marketplace.php';
require_once __DIR__ . '/includes/header.php';

$top_services = [];
$error = null;

try {
    $top_services = marketplace_fetch_service_stats(5);
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>

            <section class="content-section">
                <p class="service-crumb"><a href="products.php">Services</a></p>
                <h1>Top 5 Services</h1>
                <p>Ranked by highest average rating, then most reviews, then most visits.</p>

                <?php if ($error !== null): ?>
                <p class="login-error"><?php echo htmlspecialchars($error); ?></p>
                <?php elseif (empty($top_services)): ?>
                <p class="no-contacts">No service data is available yet.</p>
                <?php else: ?>
                <div class="top-services-list">
                    <?php foreach ($top_services as $index => $svc): ?>
                    <article class="top-service-card">
                        <span class="top-service-rank">#<?php echo $index + 1; ?></span>
                        <img src="<?php echo htmlspecialchars($svc['image']); ?>" width="240" height="135" alt="">
                        <div class="top-service-body">
                            <h2><a href="<?php echo htmlspecialchars($svc['href']); ?>"><?php echo htmlspecialchars($svc['title']); ?></a></h2>
                            <p><?php echo htmlspecialchars($svc['short']); ?></p>
                            <dl class="service-stats">
                                <div>
                                    <dt>Average rating</dt>
                                    <dd><?php echo htmlspecialchars(number_format((float) $svc['avg_rating'], 2)); ?> / 5</dd>
                                </div>
                                <div>
                                    <dt>Reviews</dt>
                                    <dd><?php echo (int) $svc['review_count']; ?></dd>
                                </div>
                                <div>
                                    <dt>Visits</dt>
                                    <dd><?php echo (int) $svc['visit_count']; ?></dd>
                                </div>
                            </dl>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
