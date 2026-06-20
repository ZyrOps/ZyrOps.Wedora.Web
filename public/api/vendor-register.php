<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$required = ['business_name', 'category', 'city', 'contact_name', 'email'];
foreach ($required as $field) {
    if (trim((string) ($input[$field] ?? '')) === '') {
        wedora_json_response(['ok' => false, 'message' => 'Please complete the required fields'], 422);
    }
}

if (!filter_var((string) $input['email'], FILTER_VALIDATE_EMAIL)) {
    wedora_json_response(['ok' => false, 'message' => 'Enter a valid email'], 422);
}

wedora_store_vendor_registration([
    'business_name' => trim((string) $input['business_name']),
    'category' => trim((string) $input['category']),
    'city' => trim((string) $input['city']),
    'contact_name' => trim((string) $input['contact_name']),
    'email' => trim((string) $input['email']),
    'phone' => trim((string) ($input['phone'] ?? '')),
    'price_range' => trim((string) ($input['price_range'] ?? '')),
    'message' => trim((string) ($input['message'] ?? '')),
]);

wedora_json_response([
    'ok' => true,
    'message' => 'Application submitted. It now appears in the vendor dashboard for this browser.',
]);
