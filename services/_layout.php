<?php
if (!isset($service_slug) || !is_string($service_slug)) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Invalid service.');
}

$site_root_prefix = '../';
require_once __DIR__ . '/../includes/recent_services.php';
require_once __DIR__ . '/../includes/service_marketplace.php';
require_once __DIR__ . '/../includes/service_bodies.php';

service_record_visit($service_slug);

$svc = service_by_slug($service_slug);
$body = service_body($service_slug);

if (!$svc || !$body) {
    header('HTTP/1.0 404 Not Found');
    exit('Service not found.');
}

$marketplace_user_email = marketplace_current_user_email();
$visit_error = null;
$review_errors = [];
$review_success = false;
$review_fields = [
    'user_email' => $marketplace_user_email,
    'rating' => '5',
    'review_text' => '',
];

try {
    marketplace_record_service_visit($service_slug, $marketplace_user_email);
} catch (Throwable $e) {
    $visit_error = 'Database visit tracking is not available: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_review_form'])) {
    $review_fields['user_email'] = trim((string) ($_POST['user_email'] ?? ''));
    $review_fields['rating'] = trim((string) ($_POST['rating'] ?? ''));
    $review_fields['review_text'] = trim((string) ($_POST['review_text'] ?? ''));

    if ($review_fields['user_email'] === '' || !filter_var($review_fields['user_email'], FILTER_VALIDATE_EMAIL)) {
        $review_errors['user_email'] = 'Enter a valid user email.';
    }
    $rating_value = filter_var($review_fields['rating'], FILTER_VALIDATE_INT);
    if ($rating_value === false || $rating_value < 1 || $rating_value > 5) {
        $review_errors['rating'] = 'Choose a rating from 1 to 5.';
    }
    if ($review_fields['review_text'] === '') {
        $review_errors['review_text'] = 'Review text is required.';
    }

    if (empty($review_errors)) {
        try {
            marketplace_create_service_review(
                $service_slug,
                $review_fields['user_email'],
                (int) $rating_value,
                $review_fields['review_text']
            );
            $review_success = true;
            $marketplace_user_email = $review_fields['user_email'];
            $review_fields['review_text'] = '';
        } catch (Throwable $e) {
            $review_errors['database'] = $e->getMessage();
        }
    }
}

$reviews = [];
$reviews_error = null;
try {
    $reviews = marketplace_fetch_service_reviews($service_slug);
} catch (Throwable $e) {
    $reviews_error = 'Could not load reviews: ' . $e->getMessage();
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
                <?php if ($visit_error !== null): ?>
                <p class="login-error"><?php echo htmlspecialchars($visit_error); ?></p>
                <?php endif; ?>

                <section class="service-marketplace-panel">
                    <h2>Reviews and ratings</h2>
                    <?php if ($review_success): ?>
                    <p class="form-success">Review saved successfully.</p>
                    <?php endif; ?>
                    <?php if (isset($review_errors['database'])): ?>
                    <p class="login-error"><?php echo htmlspecialchars($review_errors['database']); ?></p>
                    <?php endif; ?>

                    <form class="login-form review-form" method="post">
                        <input type="hidden" name="service_review_form" value="1">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="user_email">User email</label>
                                <input id="user_email" name="user_email" type="email" value="<?php echo htmlspecialchars($review_fields['user_email']); ?>" required>
                                <?php if (isset($review_errors['user_email'])): ?><small><?php echo htmlspecialchars($review_errors['user_email']); ?></small><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <select id="rating" name="rating" required>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <option value="<?php echo $i; ?>"<?php echo (string) $i === $review_fields['rating'] ? ' selected' : ''; ?>><?php echo $i; ?> / 5</option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (isset($review_errors['rating'])): ?><small><?php echo htmlspecialchars($review_errors['rating']); ?></small><?php endif; ?>
                            </div>
                            <div class="form-group form-group-wide">
                                <label for="review_text">Review</label>
                                <textarea id="review_text" name="review_text" rows="4" required><?php echo htmlspecialchars($review_fields['review_text']); ?></textarea>
                                <?php if (isset($review_errors['review_text'])): ?><small><?php echo htmlspecialchars($review_errors['review_text']); ?></small><?php endif; ?>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Add review</button>
                    </form>

                    <?php if ($reviews_error !== null): ?>
                    <p class="login-error"><?php echo htmlspecialchars($reviews_error); ?></p>
                    <?php elseif (empty($reviews)): ?>
                    <p class="no-contacts">No reviews yet.</p>
                    <?php else: ?>
                    <div class="review-list">
                        <?php foreach ($reviews as $review): ?>
                        <article class="review-card">
                            <div class="review-meta">
                                <strong><?php echo htmlspecialchars((string) $review['rating']); ?> / 5</strong>
                                <span><?php echo htmlspecialchars((string) $review['user_email']); ?></span>
                                <time datetime="<?php echo htmlspecialchars((string) $review['created_at']); ?>"><?php echo htmlspecialchars((string) $review['created_at']); ?></time>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars((string) $review['review_text'])); ?></p>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </section>

                <p><a class="btn btn-primary" href="<?php echo htmlspecialchars($site_root_prefix); ?>products.php">Back to all services</a></p>
            </section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
