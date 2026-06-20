<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$message = trim((string) ($input['message'] ?? ''));

if ($message === '') {
    wedora_json_response(['ok' => false, 'message' => 'Message is required'], 422);
}

wedora_append_chat('user', $message);

$apiKey = (string) wedora_env('ANTHROPIC_API_KEY', '');
$reply = '';

if ($apiKey !== '' && function_exists('curl_init')) {
    $payload = [
        'model' => (string) wedora_env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
        'max_tokens' => 420,
        'system' => 'You are Wedora, a concise Kerala wedding planning concierge. Ask practical follow-up questions and suggest vendor categories, cities, budgets, and planning next steps. Do not claim to book vendors directly.',
        'messages' => [
            ['role' => 'user', 'content' => $message],
        ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 20,
    ]);
    $raw = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if ($status >= 200 && $status < 300 && is_array($decoded)) {
        $reply = trim((string) ($decoded['content'][0]['text'] ?? ''));
    }
}

if ($reply === '') {
    $lower = mb_strtolower($message);
    $city = 'Kochi';
    foreach (wedora_cities() as $candidate) {
        if (str_contains($lower, mb_strtolower($candidate))) {
            $city = $candidate;
            break;
        }
    }

    $reply = 'Start with a ' . $city . ' shortlist across venues, photography, decor, catering, and makeup. Save 2-3 options in each category, then compare availability, guest capacity, and the minimum spend before you send enquiries.';
}

wedora_append_chat('assistant', $reply);

wedora_json_response([
    'ok' => true,
    'reply' => $reply,
    'history' => wedora_chat_history(),
]);
