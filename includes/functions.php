<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function wedora_data(string $name): array
{
    $path = WEDORA_ROOT . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $name . '.php';
    if (!is_file($path)) {
        return [];
    }

    $data = require $path;
    return is_array($data) ? $data : [];
}

function wedora_categories(): array
{
    return wedora_data('categories');
}

function wedora_cities(): array
{
    return wedora_data('cities');
}

function wedora_budgets(): array
{
    return wedora_data('budgets');
}

function wedora_vendors(): array
{
    return wedora_data('vendors');
}

function wedora_weddings(): array
{
    return wedora_data('weddings');
}

function wedora_find_vendor(string $id): ?array
{
    foreach (wedora_vendors() as $vendor) {
        if (($vendor['id'] ?? '') === $id) {
            return $vendor;
        }
    }

    return null;
}

function wedora_find_wedding(string $id): ?array
{
    foreach (wedora_weddings() as $wedding) {
        if (($wedding['id'] ?? '') === $id) {
            return $wedding;
        }
    }

    return null;
}

function wedora_session_key(): string
{
    return session_id();
}

function wedora_json_input(): array
{
    $raw = file_get_contents('php://input') ?: '';
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        return $decoded;
    }

    return $_POST;
}

function wedora_json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function wedora_saved_vendor_ids(): array
{
    $pdo = wedora_db();
    if ($pdo) {
        try {
            $statement = $pdo->prepare('SELECT vendor_id FROM saved_vendors WHERE session_id = ? ORDER BY created_at DESC');
            $statement->execute([wedora_session_key()]);
            return array_values(array_map('strval', $statement->fetchAll(PDO::FETCH_COLUMN)));
        } catch (Throwable) {
            // Fall through to the session-backed store when the optional DB is not migrated yet.
        }
    }

    $_SESSION['saved_vendors'] ??= [];
    return array_values(array_unique(array_map('strval', $_SESSION['saved_vendors'])));
}

function wedora_is_saved(string $vendorId): bool
{
    return in_array($vendorId, wedora_saved_vendor_ids(), true);
}

function wedora_toggle_saved_vendor(string $vendorId): bool
{
    if (!wedora_find_vendor($vendorId)) {
        return false;
    }

    $pdo = wedora_db();
    $currentlySaved = wedora_is_saved($vendorId);

    if ($pdo) {
        try {
            if ($currentlySaved) {
                $statement = $pdo->prepare('DELETE FROM saved_vendors WHERE session_id = ? AND vendor_id = ?');
                $statement->execute([wedora_session_key(), $vendorId]);
            } else {
                $statement = $pdo->prepare('INSERT IGNORE INTO saved_vendors (session_id, vendor_id) VALUES (?, ?)');
                $statement->execute([wedora_session_key(), $vendorId]);
            }
        } catch (Throwable) {
            // Keep the UI functional even if the optional DB is unavailable.
        }
    }

    $_SESSION['saved_vendors'] = wedora_saved_vendor_ids();
    if ($currentlySaved) {
        $_SESSION['saved_vendors'] = array_values(array_filter(
            $_SESSION['saved_vendors'],
            fn (string $id): bool => $id !== $vendorId
        ));
        return false;
    }

    $_SESSION['saved_vendors'][] = $vendorId;
    $_SESSION['saved_vendors'] = array_values(array_unique($_SESSION['saved_vendors']));
    return true;
}

function wedora_checklist_items(): array
{
    return [
        ['id' => 'venue', 'label' => 'Shortlist venues', 'phase' => '12 months out'],
        ['id' => 'photo', 'label' => 'Book photography and film', 'phase' => '10 months out'],
        ['id' => 'makeup', 'label' => 'Schedule bridal makeup trials', 'phase' => '8 months out'],
        ['id' => 'decor', 'label' => 'Freeze decor direction', 'phase' => '6 months out'],
        ['id' => 'catering', 'label' => 'Confirm sadya and reception menus', 'phase' => '4 months out'],
        ['id' => 'timeline', 'label' => 'Share final wedding-day timeline', 'phase' => '2 weeks out'],
    ];
}

function wedora_completed_checklist_ids(): array
{
    $pdo = wedora_db();
    if ($pdo) {
        try {
            $statement = $pdo->prepare('SELECT item_id FROM checklist_items WHERE session_id = ? AND completed = 1');
            $statement->execute([wedora_session_key()]);
            return array_values(array_map('strval', $statement->fetchAll(PDO::FETCH_COLUMN)));
        } catch (Throwable) {
            // Fall back to session storage.
        }
    }

    $_SESSION['checklist_completed'] ??= [];
    return array_values(array_unique(array_map('strval', $_SESSION['checklist_completed'])));
}

function wedora_toggle_checklist_item(string $itemId, ?bool $completed = null): bool
{
    $validIds = array_column(wedora_checklist_items(), 'id');
    if (!in_array($itemId, $validIds, true)) {
        return false;
    }

    $completedIds = wedora_completed_checklist_ids();
    $isCompleted = in_array($itemId, $completedIds, true);
    $next = $completed ?? !$isCompleted;

    if ($next && !$isCompleted) {
        $completedIds[] = $itemId;
    }
    if (!$next) {
        $completedIds = array_values(array_filter($completedIds, fn (string $id): bool => $id !== $itemId));
    }

    $_SESSION['checklist_completed'] = array_values(array_unique($completedIds));

    $pdo = wedora_db();
    if ($pdo) {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO checklist_items (session_id, item_id, completed, updated_at)
                 VALUES (?, ?, ?, CURRENT_TIMESTAMP)
                 ON DUPLICATE KEY UPDATE completed = VALUES(completed), updated_at = CURRENT_TIMESTAMP'
            );
            $statement->execute([wedora_session_key(), $itemId, $next ? 1 : 0]);
        } catch (Throwable) {
            // Session state has already been updated.
        }
    }

    return $next;
}

function wedora_checklist_progress(): int
{
    $total = count(wedora_checklist_items());
    if ($total === 0) {
        return 0;
    }

    return (int) round(count(wedora_completed_checklist_ids()) / $total * 100);
}

function wedora_store_enquiry(array $payload): void
{
    $_SESSION['enquiries'] ??= [];
    $_SESSION['enquiries'][] = $payload + ['created_at' => date('c')];

    $pdo = wedora_db();
    if (!$pdo) {
        return;
    }

    try {
        $statement = $pdo->prepare(
            'INSERT INTO enquiries (session_id, vendor_id, name, email, phone, event_date, message)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            wedora_session_key(),
            $payload['vendor_id'] ?? '',
            $payload['name'] ?? '',
            $payload['email'] ?? '',
            $payload['phone'] ?? '',
            $payload['event_date'] ?? null,
            $payload['message'] ?? '',
        ]);
    } catch (Throwable) {
        // The session copy preserves the request for the visitor.
    }
}

function wedora_chat_history(): array
{
    $_SESSION['concierge_chat'] ??= [
        ['role' => 'assistant', 'content' => 'Tell me the city, guest count, mood, and budget. I will help shape the shortlist.'],
    ];
    return $_SESSION['concierge_chat'];
}

function wedora_append_chat(string $role, string $content): void
{
    $_SESSION['concierge_chat'] ??= [];
    $_SESSION['concierge_chat'][] = [
        'role' => $role,
        'content' => trim($content),
        'created_at' => date('c'),
    ];

    $pdo = wedora_db();
    if (!$pdo) {
        return;
    }

    try {
        $statement = $pdo->prepare('INSERT INTO chat_messages (session_id, role, content) VALUES (?, ?, ?)');
        $statement->execute([wedora_session_key(), $role, trim($content)]);
    } catch (Throwable) {
        // Session history remains available.
    }
}

function wedora_store_vendor_registration(array $payload): void
{
    $_SESSION['vendor_registrations'] ??= [];
    $_SESSION['vendor_registrations'][] = $payload + ['created_at' => date('c')];

    $pdo = wedora_db();
    if (!$pdo) {
        return;
    }

    try {
        $statement = $pdo->prepare(
            'INSERT INTO vendor_registrations (session_id, business_name, category, city, contact_name, email, phone, price_range, message)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            wedora_session_key(),
            $payload['business_name'] ?? '',
            $payload['category'] ?? '',
            $payload['city'] ?? '',
            $payload['contact_name'] ?? '',
            $payload['email'] ?? '',
            $payload['phone'] ?? '',
            $payload['price_range'] ?? '',
            $payload['message'] ?? '',
        ]);
    } catch (Throwable) {
        // Session copy is sufficient for the no-login demo flow.
    }
}

function wedora_theme(): string
{
    return ($_SESSION['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
}

function wedora_set_theme(string $theme): string
{
    $_SESSION['theme'] = $theme === 'dark' ? 'dark' : 'light';
    return $_SESSION['theme'];
}

function wedora_excerpt(string $text, int $length = 130): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $length - 3)) . '...';
}
