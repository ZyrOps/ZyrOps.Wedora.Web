<?php
declare(strict_types=1);

$pageTitle = 'Mood Board';
$activePage = 'saved';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/vendor-card.php';

$savedIds = wedora_saved_vendor_ids();
$vendors = array_values(array_filter(wedora_vendors(), fn (array $vendor): bool => in_array($vendor['id'], $savedIds, true)));
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">Mood Board</span>
    <h1 class="font-display">Saved vendors.</h1>
    <p>Your no-signup shortlist for comparing Kerala wedding teams.</p>
</section>

<section class="section">
    <div class="results-toolbar">
        <div>
            <span class="eyebrow">Shortlist</span>
            <h2 class="font-display"><span data-results-count><?= count($vendors) ?></span> saved vendors</h2>
        </div>
        <a class="btn-secondary" href="discover.php">Discover more <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="vendor-grid" data-results>
        <?php if ($vendors): ?>
            <?php foreach ($vendors as $vendor): ?>
                <?= render_vendor_card($vendor) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state card">
                <?= icon('heart', 28) ?>
                <h3 class="font-display">Your mood board is empty</h3>
                <p>Save vendors from Discover and they will appear here.</p>
                <a class="btn-primary" href="discover.php">Start shortlisting</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
