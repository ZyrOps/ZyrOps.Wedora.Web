<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';

function render_vendor_card(array $vendor, bool $compact = false): string
{
    $saved = wedora_is_saved((string) $vendor['id']);
    $classes = 'vendor-card card card-hover fade-up' . ($compact ? ' vendor-card-compact' : '');

    ob_start();
    ?>
    <article class="<?= h($classes) ?>" data-vendor-card data-vendor-id="<?= h($vendor['id']) ?>">
        <a class="vendor-card-media <?= h($vendor['gradient']) ?>" href="vendor.php?id=<?= urlencode((string) $vendor['id']) ?>" aria-label="<?= h($vendor['name']) ?>">
            <?php if (!empty($vendor['verified'])): ?>
                <span class="verified-badge"><span class="verified-dot"></span>Verified</span>
            <?php endif; ?>
            <span class="vendor-card-category"><?= h($vendor['category']) ?></span>
        </a>
        <div class="vendor-card-body">
            <div class="vendor-card-topline">
                <span class="meta-pill"><?= icon('map-pin', 13) ?><?= h($vendor['city']) ?></span>
                <span class="rating"><?= icon('star', 14, '#b88a4a') ?><?= h($vendor['rating']) ?> <span><?= h($vendor['review_count']) ?></span></span>
            </div>
            <h3 class="vendor-card-title font-display"><a href="vendor.php?id=<?= urlencode((string) $vendor['id']) ?>"><?= h($vendor['name']) ?></a></h3>
            <p class="vendor-card-copy"><?= h($vendor['tagline']) ?></p>
            <div class="vendor-card-footer">
                <span class="vendor-price"><?= h($vendor['price']) ?></span>
                <button class="save-button <?= $saved ? 'saved' : '' ?>" type="button" data-save-vendor="<?= h($vendor['id']) ?>" aria-label="<?= $saved ? 'Remove from mood board' : 'Save to mood board' ?>">
                    <?= icon('heart', 18) ?>
                </button>
            </div>
        </div>
    </article>
    <?php
    return trim((string) ob_get_clean());
}
