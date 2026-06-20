<?php
declare(strict_types=1);

$pageTitle = 'Vendor Dashboard';
$activePage = 'vendor-register';
require_once __DIR__ . '/../includes/header.php';

$registrations = array_reverse($_SESSION['vendor_registrations'] ?? []);
$enquiries = array_reverse($_SESSION['enquiries'] ?? []);
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">Vendor dashboard</span>
    <h1 class="font-display">Session activity.</h1>
    <p>Recent vendor applications and enquiries submitted from this browser.</p>
</section>

<section class="dashboard-grid">
    <div class="dashboard-panel card">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Applications</span>
                <h2 class="font-display"><?= count($registrations) ?> submitted</h2>
            </div>
            <?= icon('briefcase', 22) ?>
        </div>
        <div class="dashboard-list">
            <?php if ($registrations): ?>
                <?php foreach ($registrations as $registration): ?>
                    <article class="dashboard-item">
                        <strong><?= h($registration['business_name'] ?? 'Vendor') ?></strong>
                        <span><?= h($registration['category'] ?? '') ?> / <?= h($registration['city'] ?? '') ?></span>
                        <small><?= h($registration['email'] ?? '') ?></small>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted-copy">No applications yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-panel card">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Enquiries</span>
                <h2 class="font-display"><?= count($enquiries) ?> received</h2>
            </div>
            <?= icon('mail', 22) ?>
        </div>
        <div class="dashboard-list">
            <?php if ($enquiries): ?>
                <?php foreach ($enquiries as $enquiry): ?>
                    <?php $vendor = wedora_find_vendor((string) ($enquiry['vendor_id'] ?? '')); ?>
                    <article class="dashboard-item">
                        <strong><?= h($vendor['name'] ?? 'Vendor enquiry') ?></strong>
                        <span><?= h($enquiry['name'] ?? '') ?> / <?= h($enquiry['email'] ?? '') ?></span>
                        <small><?= h(wedora_excerpt((string) ($enquiry['message'] ?? ''), 90)) ?></small>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted-copy">No enquiries yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
