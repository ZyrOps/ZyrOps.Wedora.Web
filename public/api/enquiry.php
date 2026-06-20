<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$vendorId = trim((string) ($input['vendor_id'] ?? ''));
$name = trim((string) ($input['name'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$message = trim((string) ($input['message'] ?? ''));

if ($vendorId === '' || !wedora_find_vendor($vendorId)) {
    wedora_json_response(['ok' => false, 'message' => 'Vendor not found'], 404);
}
if ($name === '' || $email === '' || $message === '') {
    wedora_json_response(['ok' => false, 'message' => 'Name, email, and message are required'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    wedora_json_response(['ok' => false, 'message' => 'Enter a valid email'], 422);
}

wedora_store_enquiry([
    'vendor_id' => $vendorId,
    'name' => $name,
    'email' => $email,
    'phone' => trim((string) ($input['phone'] ?? '')),
    'event_date' => trim((string) ($input['event_date'] ?? '')),
    'message' => $message,
]);

wedora_json_response([
    'ok' => true,
    'message' => 'Enquiry sent. The vendor dashboard has a copy for this session.',
]);
