<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$wedding = wedora_find_wedding((string) ($_GET['id'] ?? ''));
if (!$wedding) {
    http_response_code(404);
    $pageTitle = 'Wedding not found';
    $activePage = 'weddings';
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <section class="page-hero compact-hero">
        <span class="eyebrow">404</span>
        <h1 class="font-display">Wedding not found.</h1>
        <a class="btn-primary" href="weddings.php">Back to weddings</a>
    </section>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = (string) $wedding['title'];
$activePage = 'weddings';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="wedding-detail-hero <?= h($wedding['gradient']) ?>">
    <a class="back-link light" href="weddings.php"><?= icon('chevron-left', 16) ?>Weddings</a>
    <span class="eyebrow">Wedding story</span>
    <h1 class="font-display"><?= h($wedding['title']) ?></h1>
    <p><?= h($wedding['subtitle']) ?></p>
</section>

<section class="section wedding-story-layout">
    <article class="story-article">
        <span class="eyebrow"><?= h($wedding['city']) ?> / <?= h($wedding['season']) ?> / <?= h($wedding['date']) ?></span>
        <h2 class="font-display">The day</h2>
        <p><?= h($wedding['story']) ?></p>
    </article>
    <aside class="story-sidebar card">
        <h3 class="font-display">Palette</h3>
        <div class="palette-list">
            <?php foreach ($wedding['palette'] as $color): ?>
                <span><?= h($color) ?></span>
            <?php endforeach; ?>
        </div>
        <hr>
        <h3 class="font-display">Vendor mix</h3>
        <ul class="clean-list">
            <?php foreach ($wedding['vendors'] as $vendor): ?>
                <li><?= icon('check', 15) ?><?= h($vendor) ?></li>
            <?php endforeach; ?>
        </ul>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
