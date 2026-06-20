<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/filters.php';
require_once __DIR__ . '/../../includes/vendor-card.php';

$filters = wedora_active_filters($_GET);
$vendors = wedora_filter_vendors($filters);
$html = '';

if ($vendors) {
    foreach ($vendors as $vendor) {
        $html .= render_vendor_card($vendor);
    }
} else {
    $html = '<div class="empty-state card">' . icon('search', 28) . '<h3 class="font-display">No vendors found</h3><p>Try a wider city, budget, or style.</p></div>';
}

wedora_json_response([
    'ok' => true,
    'count' => count($vendors),
    'html' => $html,
]);
