<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../db.php';

function rbac_normalize_roles($roles): array
{
    if (!is_array($roles)) {
        return [];
    }

    $roles = array_values(array_unique(array_filter(array_map('strval', $roles))));
    $priority = ['admin', 'manager', 'support', 'user'];

    usort($roles, static function (string $left, string $right) use ($priority): int {
        $leftIndex = array_search($left, $priority, true);
        $rightIndex = array_search($right, $priority, true);

        $leftIndex = $leftIndex === false ? PHP_INT_MAX : $leftIndex;
        $rightIndex = $rightIndex === false ? PHP_INT_MAX : $rightIndex;

        if ($leftIndex === $rightIndex) {
            return strcmp($left, $right);
        }

        return $leftIndex <=> $rightIndex;
    });

    return $roles;
}

function rbac_set_session_roles(PDO $pdo, ?int $userId = null): array
{
    if ($userId === null) {
        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
    }

    $roles = [];

    if ($userId > 0) {
        $stmt = $pdo->prepare('SELECT r.name FROM roles r INNER JOIN user_roles ur ON ur.role_id = r.id WHERE ur.user_id = ? ORDER BY r.name ASC');
        $stmt->execute([$userId]);
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    if (!empty($_SESSION['roles']) && is_array($_SESSION['roles'])) {
        $roles = array_values(array_unique(array_merge($roles, array_map('strval', $_SESSION['roles']))));
    }

    $roles = rbac_normalize_roles($roles);

    if (empty($roles)) {
        $roles = ['user'];
    }

    $_SESSION['roles'] = $roles;
    $_SESSION['role'] = $roles[0];

    return $roles;
}

function current_user_roles(): array
{
    return rbac_normalize_roles($_SESSION['roles'] ?? (isset($_SESSION['role']) ? [$_SESSION['role']] : []));
}

function current_user_has_role($roles): bool
{
    $roles = is_array($roles) ? $roles : [$roles];
    $roles = array_map('strval', $roles);

    return (bool) array_intersect(current_user_roles(), $roles);
}

function requireLogin(string $redirectTo = '../auth/login.php'): void
{
    $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
    $roles = current_user_roles();

    if ($userId <= 0 && empty($roles)) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

function auth_primary_role(): string
{
    $roles = current_user_roles();

    return $roles[0] ?? 'user';
}

function auth_sync_current_user(PDO $pdo): array
{
    return rbac_set_session_roles($pdo);
}
