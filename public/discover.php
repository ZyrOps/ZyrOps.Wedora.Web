<?php
declare(strict_types=1);

$pageTitle = 'Discover';
$activePage = 'discover';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/vendor-card.php';
require_once __DIR__ . '/../includes/filters.php';

$filters = wedora_active_filters($_GET);
$vendors = wedora_filter_vendors($filters);
$styles = wedora_all_styles();
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">Discover</span>
    <h1 class="font-display">Find the right Kerala wedding team.</h1>
    <p>Filter by category, city, budget, and mood. Save the vendors you want to revisit.</p>
</section>

<section class="discover-layout">
    <aside class="filter-panel card">
        <form method="get" action="discover.php" data-filter-form>
            <label class="filter-search">
                <?= icon('search', 17) ?>
                <input class="input-field" type="search" name="q" value="<?= h($filters['q']) ?>" placeholder="Search vendors">
            </label>

            <input type="hidden" name="category" value="<?= h($filters['category']) ?>" data-filter-hidden="category">
            <input type="hidden" name="city" value="<?= h($filters['city']) ?>" data-filter-hidden="city">
            <input type="hidden" name="budget" value="<?= h($filters['budget']) ?>" data-filter-hidden="budget">
            <input type="hidden" name="style" value="<?= h($filters['style']) ?>" data-filter-hidden="style">

            <div class="filter-group">
                <span>Category</span>
                <div class="chip-wrap">
                    <?php foreach (wedora_categories() as $category): ?>
                        <?php $name = (string) $category['name']; ?>
                        <button class="chip-filter <?= $filters['category'] === $name ? 'active' : '' ?>" type="button" data-filter-name="category" data-filter-value="<?= h($name) ?>"><?= h($name) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <span>City</span>
                <div class="chip-wrap">
                    <?php foreach (wedora_cities() as $city): ?>
                        <button class="chip-filter <?= $filters['city'] === $city ? 'active' : '' ?>" type="button" data-filter-name="city" data-filter-value="<?= h($city) ?>"><?= h($city) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <span>Budget</span>
                <div class="chip-wrap">
                    <?php foreach (wedora_budgets() as $budget): ?>
                        <button class="chip-filter <?= $filters['budget'] === $budget ? 'active' : '' ?>" type="button" data-filter-name="budget" data-filter-value="<?= h($budget) ?>"><?= h($budget) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-group">
                <span>Style</span>
                <div class="chip-wrap">
                    <?php foreach ($styles as $style): ?>
                        <button class="chip-filter <?= $filters['style'] === $style ? 'active' : '' ?>" type="button" data-filter-name="style" data-filter-value="<?= h($style) ?>"><?= h($style) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn-primary" type="submit"><?= icon('filter', 16) ?>Apply</button>
                <a class="btn-secondary" href="discover.php">Clear</a>
            </div>
        </form>
    </aside>

    <div class="results-panel">
        <div class="results-toolbar">
            <div>
                <span class="eyebrow">Results</span>
                <h2 class="font-display"><span data-results-count><?= count($vendors) ?></span> vendors</h2>
            </div>
            <a class="btn-ghost" href="saved.php"><?= icon('bookmark', 15) ?>Mood board</a>
        </div>

        <div class="vendor-grid" data-results>
            <?php if ($vendors): ?>
                <?php foreach ($vendors as $vendor): ?>
                    <?= render_vendor_card($vendor) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state card">
                    <?= icon('search', 28) ?>
                    <h3 class="font-display">No vendors found</h3>
                    <p>Try a wider city, budget, or style.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
