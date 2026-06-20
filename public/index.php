<?php
declare(strict_types=1);

$pageTitle = 'Home';
$activePage = 'home';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/vendor-card.php';

$categories = wedora_categories();
$vendors = array_slice(wedora_vendors(), 0, 3);
$weddings = array_slice(wedora_weddings(), 0, 2);
?>

<section class="hero grad-hero">
    <div class="hero-copy fade-up">
        <span class="eyebrow">Kerala wedding-vendor marketplace</span>
        <h1 class="font-display">Wedora</h1>
        <p>Find venues, artists, decor teams, caterers, and planners with a mood board that stays with you.</p>
        <form class="hero-search" action="discover.php" method="get">
            <label class="search-field">
                <?= icon('search', 18) ?>
                <input type="search" name="q" placeholder="Search photography, venues, decor">
            </label>
            <button class="btn-primary" type="submit">Discover vendors <?= icon('arrow-right', 17) ?></button>
        </form>
        <div class="hero-vendor-strip">
            <?php foreach ($vendors as $vendor): ?>
                <a class="mini-vendor" href="vendor.php?id=<?= urlencode((string) $vendor['id']) ?>">
                    <span class="mini-swatch <?= h($vendor['gradient']) ?>"></span>
                    <span>
                        <strong><?= h($vendor['name']) ?></strong>
                        <small><?= h($vendor['category']) ?> in <?= h($vendor['city']) ?></small>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <span class="eyebrow">Browse by craft</span>
        <h2 class="font-display">Curated categories</h2>
    </div>
    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
            <a class="category-tile card card-hover" href="discover.php?category=<?= urlencode((string) $category['name']) ?>">
                <span><?= icon((string) $category['icon'], 22) ?></span>
                <strong class="font-display"><?= h($category['name']) ?></strong>
                <small><?= h($category['copy']) ?></small>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section split-section">
    <div>
        <div class="section-heading">
            <span class="eyebrow">Featured vendors</span>
            <h2 class="font-display">A first shortlist</h2>
        </div>
        <div class="vendor-grid vendor-grid-three">
            <?php foreach ($vendors as $vendor): ?>
                <?= render_vendor_card($vendor) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="plan-callout">
        <span><?= icon('sparkles', 24) ?></span>
        <h3 class="font-display">Concierge planning</h3>
        <p>Use the planning board to track tasks and ask for a shortlist by city, guest count, mood, and budget.</p>
        <a class="btn-secondary" href="plan.php">Open planner <?= icon('arrow-right', 16) ?></a>
    </aside>
</section>

<section class="section">
    <div class="section-heading section-heading-row">
        <div>
            <span class="eyebrow">Real Kerala weddings</span>
            <h2 class="font-display">Stories to borrow from</h2>
        </div>
        <a class="btn-ghost" href="weddings.php">View all <?= icon('arrow-right', 15) ?></a>
    </div>
    <div class="story-grid">
        <?php foreach ($weddings as $wedding): ?>
            <a class="story-card card card-hover" href="wedding.php?id=<?= urlencode((string) $wedding['id']) ?>">
                <span class="story-media <?= h($wedding['gradient']) ?>"></span>
                <span class="story-body">
                    <small><?= h($wedding['city']) ?> / <?= h($wedding['season']) ?></small>
                    <strong class="font-display"><?= h($wedding['title']) ?></strong>
                    <span><?= h($wedding['excerpt']) ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
