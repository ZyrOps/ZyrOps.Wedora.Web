<?php
declare(strict_types=1);

$pageTitle = 'Plan';
$activePage = 'plan';
require_once __DIR__ . '/../includes/header.php';

$completed = wedora_completed_checklist_ids();
$items = wedora_checklist_items();
$progress = wedora_checklist_progress();
$history = wedora_chat_history();
?>

<section class="page-hero compact-hero">
    <span class="eyebrow">Plan</span>
    <h1 class="font-display">Checklist and concierge.</h1>
    <p>Track the essentials and ask for a vendor shortlist without creating an account.</p>
</section>

<section class="planner-layout">
    <div class="checklist-panel card">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Checklist</span>
                <h2 class="font-display"><?= $progress ?>% complete</h2>
            </div>
            <span class="progress-ring" style="--progress: <?= $progress ?>"><?= $progress ?></span>
        </div>
        <div class="progress-bar"><span style="width: <?= $progress ?>%"></span></div>
        <div class="checklist" data-checklist>
            <?php foreach ($items as $item): ?>
                <?php $isDone = in_array($item['id'], $completed, true); ?>
                <label class="check-item <?= $isDone ? 'done' : '' ?>">
                    <input type="checkbox" data-checklist-item="<?= h($item['id']) ?>" <?= $isDone ? 'checked' : '' ?>>
                    <span class="fake-check"><?= icon('check', 14) ?></span>
                    <span>
                        <strong><?= h($item['label']) ?></strong>
                        <small><?= h($item['phase']) ?></small>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="concierge-panel card">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">AI concierge</span>
                <h2 class="font-display">Ask Wedora</h2>
            </div>
            <?= icon('message', 22) ?>
        </div>
        <div class="chat-window" data-chat-window>
            <?php foreach ($history as $message): ?>
                <div class="chat-bubble <?= h($message['role']) ?>">
                    <?= h($message['content']) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <form class="chat-form" data-chat-form>
            <input class="input-field" name="message" autocomplete="off" placeholder="Kochi, 250 guests, warm traditional, Rs. 3L decor">
            <button class="btn-primary" type="submit"><?= icon('arrow-right', 16) ?></button>
        </form>
        <p class="form-status" data-chat-status></p>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
