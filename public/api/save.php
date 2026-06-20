<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$vendorId = trim((string) ($input['vendor_id'] ?? ''));

if ($vendorId === '' || !wedora_find_vendor($vendorId)) {
    wedora_json_response(['ok' => false, 'message' => 'Vendor not found'], 404);
}

$saved = wedora_toggle_saved_vendor($vendorId);

wedora_json_response([
    'ok' => true,
    'vendor_id' => $vendorId,
    'saved' => $saved,
    'count' => count(wedora_saved_vendor_ids()),
]);
