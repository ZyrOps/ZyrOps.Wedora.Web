<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$itemId = trim((string) ($input['item_id'] ?? ''));
$completed = array_key_exists('completed', $input) ? (bool) $input['completed'] : null;

if ($itemId === '') {
    wedora_json_response(['ok' => false, 'message' => 'Checklist item is required'], 422);
}

$done = wedora_toggle_checklist_item($itemId, $completed);

wedora_json_response([
    'ok' => true,
    'item_id' => $itemId,
    'completed' => $done,
    'completed_ids' => wedora_completed_checklist_ids(),
    'progress' => wedora_checklist_progress(),
]);
