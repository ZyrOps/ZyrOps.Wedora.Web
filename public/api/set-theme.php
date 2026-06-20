<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wedora_json_response(['ok' => false, 'message' => 'POST required'], 405);
}

$input = wedora_json_input();
$theme = wedora_set_theme((string) ($input['theme'] ?? 'light'));

wedora_json_response([
    'ok' => true,
    'theme' => $theme,
]);
