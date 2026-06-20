<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function wedora_active_filters(array $source): array
{
    return [
        'q' => trim((string) ($source['q'] ?? '')),
        'category' => trim((string) ($source['category'] ?? '')),
        'city' => trim((string) ($source['city'] ?? '')),
        'budget' => trim((string) ($source['budget'] ?? '')),
        'style' => trim((string) ($source['style'] ?? '')),
    ];
}

function wedora_filter_vendors(array $filters): array
{
    $vendors = wedora_vendors();
    $q = mb_strtolower($filters['q'] ?? '');
    $category = $filters['category'] ?? '';
    $city = $filters['city'] ?? '';
    $budget = $filters['budget'] ?? '';
    $style = $filters['style'] ?? '';

    return array_values(array_filter($vendors, function (array $vendor) use ($q, $category, $city, $budget, $style): bool {
        if ($category !== '' && ($vendor['category'] ?? '') !== $category) {
            return false;
        }
        if ($city !== '' && ($vendor['city'] ?? '') !== $city) {
            return false;
        }
        if ($budget !== '' && ($vendor['budget'] ?? '') !== $budget) {
            return false;
        }
        if ($style !== '' && !in_array($style, $vendor['styles'] ?? [], true)) {
            return false;
        }
        if ($q !== '') {
            $haystack = mb_strtolower(implode(' ', [
                $vendor['name'] ?? '',
                $vendor['category'] ?? '',
                $vendor['city'] ?? '',
                $vendor['tagline'] ?? '',
                implode(' ', $vendor['styles'] ?? []),
            ]));
            if (!str_contains($haystack, $q)) {
                return false;
            }
        }

        return true;
    }));
}

function wedora_all_styles(): array
{
    $styles = [];
    foreach (wedora_vendors() as $vendor) {
        foreach (($vendor['styles'] ?? []) as $style) {
            $styles[$style] = $style;
        }
    }

    return array_values($styles);
}
