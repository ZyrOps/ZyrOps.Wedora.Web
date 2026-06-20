<?php
declare(strict_types=1);

function icon(string $name, int $size = 18, string $color = 'currentColor'): string
{
    $attrs = 'width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . h($color) . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
    $paths = [
        'arrow-right' => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
        'bookmark' => '<path d="M6 4h12v17l-6-4-6 4V4Z"/>',
        'briefcase' => '<path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1"/><path d="M4 7h16v12H4z"/><path d="M4 12h16"/>',
        'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 9h18"/><rect x="3" y="4" width="18" height="18" rx="2"/>',
        'camera' => '<path d="M14.5 4 16 7h3a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h3l1.5-3h5Z"/><circle cx="12" cy="13" r="3.2"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
        'filter' => '<path d="M4 6h16"/><path d="M7 12h10"/><path d="M10 18h4"/>',
        'heart' => '<path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6L12 21l8.8-8.8a5.4 5.4 0 0 0 0-7.6Z"/>',
        'home' => '<path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'map-pin' => '<path d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11Z"/><circle cx="12" cy="10" r="2.4"/>',
        'message' => '<path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>',
        'moon' => '<path d="M21 13.4A8 8 0 1 1 10.6 3a6.5 6.5 0 0 0 10.4 10.4Z"/>',
        'palette' => '<path d="M12 22a10 10 0 1 1 10-10c0 2-1.2 3-2.8 3h-1.6a2.4 2.4 0 0 0-2.4 2.4c0 .6.2 1 .2 1.5 0 1.7-1.4 3.1-3.4 3.1Z"/><circle cx="7.5" cy="10" r=".7"/><circle cx="10" cy="6.8" r=".7"/><circle cx="14.2" cy="6.8" r=".7"/><circle cx="16.8" cy="10" r=".7"/>',
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/>',
        'sparkles' => '<path d="m12 3 1.7 4.6L18 9.3l-4.3 1.6L12 15.5l-1.7-4.6L6 9.3l4.3-1.7L12 3Z"/><path d="m19 15 .9 2.3L22 18l-2.1.7L19 21l-.9-2.3L16 18l2.1-.7L19 15Z"/><path d="m5 14 .8 2L8 17l-2.2.8L5 20l-.8-2.2L2 17l2.2-1L5 14Z"/>',
        'star' => '<path d="m12 2 3 6.2 6.8 1-4.9 4.8 1.1 6.8-6-3.2-6 3.2 1.1-6.8-4.9-4.8 6.8-1L12 2Z"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m4.9 19.1 1.4-1.4"/><path d="m17.7 6.3 1.4-1.4"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 22a8 8 0 0 1 16 0"/>',
        'x' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
    ];

    $body = $paths[$name] ?? $paths['sparkles'];
    return '<svg ' . $attrs . '>' . $body . '</svg>';
}
