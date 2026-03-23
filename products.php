<?php
require_once __DIR__ . '/includes/service_catalog.php';

$page_title = 'Services';
require_once __DIR__ . '/includes/header.php';

$catalog = service_catalog();
?>

            <section class="content-section">
                <h1>Services</h1>
                <p>We offer ten focused AI coaching modules—each with its own page, description, and artwork. Pick what matches your climb, then stack modules as you improve.</p>
                <p class="services-tools">
                    <a class="btn btn-secondary" href="recent-services.php">Last 5 services you viewed</a>
                </p>
                <div class="service-cards">
                    <?php foreach ($catalog as $slug => $svc): ?>
                    <article class="service-card">
                        <a class="service-card-media" href="<?php echo htmlspecialchars($svc['href']); ?>">
                            <img src="<?php echo htmlspecialchars($svc['image']); ?>" width="640" height="360" alt="" loading="lazy">
                        </a>
                        <div class="service-card-body">
                            <h2><a href="<?php echo htmlspecialchars($svc['href']); ?>"><?php echo htmlspecialchars($svc['title']); ?></a></h2>
                            <p><?php echo htmlspecialchars($svc['short']); ?></p>
                            <p><a class="btn btn-primary" href="<?php echo htmlspecialchars($svc['href']); ?>">View details</a></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
