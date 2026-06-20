<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';

$pageTitle = $pageTitle ?? 'Wedora';
$activePage = $activePage ?? '';
$themeClass = wedora_theme() === 'dark' ? ' theme-dark' : '';
$savedCount = count(wedora_saved_vendor_ids());
$navItems = [
    'discover' => ['Discover', 'discover.php'],
    'weddings' => ['Weddings', 'weddings.php'],
    'saved' => ['Mood Board', 'saved.php'],
    'plan' => ['Plan', 'plan.php'],
    'vendor-register' => ['For Vendors', 'vendor-register.php'],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?> | Wedora</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wedora-app<?= h($themeClass) ?>" data-theme="<?= h(wedora_theme()) ?>">
    <header class="site-header">
        <a class="brand" href="index.php" aria-label="Wedora home">
            <span class="brand-mark"><?= icon('sparkles', 18) ?></span>
            <span class="brand-word font-display">Wedora</span>
        </a>
        <nav class="site-nav" aria-label="Primary navigation">
            <?php foreach ($navItems as $key => [$label, $href]): ?>
                <a class="<?= $activePage === $key ? 'active' : '' ?>" href="<?= h($href) ?>"><?= h($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="header-actions">
            <a class="saved-link" href="saved.php" aria-label="Mood board">
                <?= icon('bookmark', 17) ?><span data-saved-count><?= $savedCount ?></span>
            </a>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="Toggle theme">
                <span class="theme-icon-light"><?= icon('sun', 17) ?></span>
                <span class="theme-icon-dark"><?= icon('moon', 17) ?></span>
            </button>
        </div>
    </header>
    <main>
