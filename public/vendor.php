<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/vendor-card.php';

$vendor = wedora_find_vendor((string) ($_GET['id'] ?? ''));
if (!$vendor) {
    http_response_code(404);
    $pageTitle = 'Vendor not found';
    $activePage = 'discover';
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <section class="page-hero compact-hero">
        <span class="eyebrow">404</span>
        <h1 class="font-display">Vendor not found.</h1>
        <p>The vendor may have moved or the link is incomplete.</p>
        <a class="btn-primary" href="discover.php">Back to discover</a>
    </section>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = (string) $vendor['name'];
$activePage = 'discover';
require_once __DIR__ . '/../includes/header.php';

$related = array_values(array_filter(wedora_vendors(), fn (array $item): bool => $item['id'] !== $vendor['id'] && $item['category'] === $vendor['category']));
$related = array_slice($related, 0, 3);
?>

<section class="vendor-profile-hero">
    <div class="profile-media <?= h($vendor['gradient']) ?>">
        <?php if (!empty($vendor['verified'])): ?>
            <span class="verified-badge"><span class="verified-dot"></span>Verified</span>
        <?php endif; ?>
    </div>
    <div class="profile-copy">
        <a class="back-link" href="discover.php"><?= icon('chevron-left', 16) ?>Discover</a>
        <span class="eyebrow"><?= h($vendor['category']) ?> / <?= h($vendor['city']) ?></span>
        <h1 class="font-display"><?= h($vendor['name']) ?></h1>
        <p><?= h($vendor['description']) ?></p>
        <div class="profile-meta">
            <span><?= icon('star', 16, '#b88a4a') ?><?= h($vendor['rating']) ?> <?= h($vendor['review_count']) ?></span>
            <span><?= icon('briefcase', 16) ?><?= h($vendor['price']) ?></span>
        </div>
        <div class="profile-actions">
            <button class="btn-primary" type="button" data-modal-open="enquiry-modal"><?= icon('mail', 17) ?>Send enquiry</button>
            <button class="btn-secondary save-inline <?= wedora_is_saved((string) $vendor['id']) ? 'saved' : '' ?>" type="button" data-save-vendor="<?= h($vendor['id']) ?>"><?= icon('heart', 17) ?><span><?= wedora_is_saved((string) $vendor['id']) ? 'Saved' : 'Save' ?></span></button>
        </div>
    </div>
</section>

<section class="section profile-section">
    <div class="profile-main">
        <div class="section-heading">
            <span class="eyebrow">Style notes</span>
            <h2 class="font-display">What they do best</h2>
        </div>
        <div class="style-list">
            <?php foreach ($vendor['styles'] as $style): ?>
                <span class="chip-filter active"><?= h($style) ?></span>
            <?php endforeach; ?>
        </div>
        <div class="highlight-grid">
            <?php foreach ($vendor['highlights'] as $highlight): ?>
                <div class="highlight-item card"><?= icon('check', 18) ?><span><?= h($highlight) ?></span></div>
            <?php endforeach; ?>
        </div>

        <div class="section-heading small-heading">
            <span class="eyebrow">Gallery</span>
            <h2 class="font-display">Gradient placeholders</h2>
        </div>
        <div class="gallery-grid">
            <?php foreach ($vendor['gallery'] as $gradient): ?>
                <span class="gallery-tile <?= h($gradient) ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="profile-sidebar card">
        <h3 class="font-display">Packages</h3>
        <ul class="clean-list">
            <?php foreach ($vendor['packages'] as $package): ?>
                <li><?= icon('sparkles', 15) ?><?= h($package) ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <p><?= icon('phone', 15) ?><?= h($vendor['contact']['phone']) ?></p>
        <p><?= icon('mail', 15) ?><?= h($vendor['contact']['email']) ?></p>
    </aside>
</section>

<?php if ($related): ?>
    <section class="section">
        <div class="section-heading">
            <span class="eyebrow">More <?= h($vendor['category']) ?></span>
            <h2 class="font-display">Related vendors</h2>
        </div>
        <div class="vendor-grid vendor-grid-three">
            <?php foreach ($related as $item): ?>
                <?= render_vendor_card($item) ?>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<div class="overlay hidden" id="enquiry-modal" data-modal>
    <div class="modal card">
        <button class="modal-close" type="button" data-modal-close aria-label="Close"><?= icon('x', 18) ?></button>
        <span class="eyebrow">Enquiry</span>
        <h2 class="font-display">Send <?= h($vendor['name']) ?> your date.</h2>
        <form data-enquiry-form>
            <input type="hidden" name="vendor_id" value="<?= h($vendor['id']) ?>">
            <div class="form-grid two">
                <label>Name<input class="input-field" name="name" required></label>
                <label>Email<input class="input-field" type="email" name="email" required></label>
                <label>Phone<input class="input-field" name="phone"></label>
                <label>Event date<input class="input-field" type="date" name="event_date"></label>
            </div>
            <label>Message<textarea class="input-field" name="message" rows="4" required>We loved your Wedora profile and would like to check availability.</textarea></label>
            <button class="btn-primary" type="submit">Send enquiry <?= icon('arrow-right', 16) ?></button>
            <p class="form-status" data-form-status></p>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
