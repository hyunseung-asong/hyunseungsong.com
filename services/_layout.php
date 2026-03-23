<?php
if (!isset($service_slug) || !is_string($service_slug)) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Invalid service.');
}

$site_root_prefix = '../';
require_once __DIR__ . '/../includes/recent_services.php';
require_once __DIR__ . '/../includes/service_bodies.php';

service_record_visit($service_slug);

$svc = service_by_slug($service_slug);
$body = service_body($service_slug);

if (!$svc || !$body) {
    header('HTTP/1.0 404 Not Found');
    exit('Service not found.');
}

$page_title = $svc['title'];
require_once __DIR__ . '/../includes/header.php';

$img = htmlspecialchars($site_root_prefix . $svc['image']);
$h1 = htmlspecialchars($svc['title']);
?>

            <section class="content-section service-detail">
                <p class="service-crumb"><a href="<?php echo htmlspecialchars($site_root_prefix); ?>products.php">Services</a></p>
                <h1><?php echo $h1; ?></h1>
                <p class="service-lede"><?php echo htmlspecialchars($body['lede']); ?></p>
                <figure class="service-figure">
                    <img src="<?php echo $img; ?>" width="960" height="540" alt="<?php echo $h1; ?> illustration">
                </figure>
                <?php foreach ($body['paras'] as $para): ?>
                <p><?php echo htmlspecialchars($para); ?></p>
                <?php endforeach; ?>
                <h2>What you get</h2>
                <ul class="service-bullets">
                    <?php foreach ($body['bullets'] as $b): ?>
                    <li><?php echo htmlspecialchars($b); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a class="btn btn-primary" href="<?php echo htmlspecialchars($site_root_prefix); ?>products.php">Back to all services</a></p>
            </section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
