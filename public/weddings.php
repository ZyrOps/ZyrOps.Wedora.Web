<?php
declare(strict_types=1);

$pageTitle = 'Weddings';
$activePage = 'weddings';
require_once __DIR__ . '/../includes/header.php';

$weddings = wedora_weddings();
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">Wedding stories</span>
    <h1 class="font-display">Real Kerala weddings.</h1>
    <p>Browse complete vendor mixes, palettes, and planning notes from wedding weekends across Kerala.</p>
</section>

<section class="section">
    <div class="story-grid story-grid-large">
        <?php foreach ($weddings as $wedding): ?>
            <a class="story-card card card-hover" href="wedding.php?id=<?= urlencode((string) $wedding['id']) ?>">
                <span class="story-media <?= h($wedding['gradient']) ?>"></span>
                <span class="story-body">
                    <small><?= h($wedding['city']) ?> / <?= h($wedding['season']) ?> / <?= h($wedding['date']) ?></small>
                    <strong class="font-display"><?= h($wedding['title']) ?></strong>
                    <span><?= h($wedding['subtitle']) ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
