<?php

require_once __DIR__ . '/auth.php';

function requireRole($roles, string $message = 'Access denied.'): void
{
    $roles = is_array($roles) ? $roles : [$roles];
    $roles = array_map('strval', $roles);

    if (!current_user_has_role($roles)) {
        http_response_code(403);
        die($message);
    }
}

function rbac_permission_map(): array
{
    return [
        'admin' => [
            'view_users',
            'create_users',
            'update_users',
            'delete_users',
            'assign_roles',
            'approve_id',
            'manage_listings',
            'view_orders',
            'manage_orders',
            'access_messages',
        ],
        'manager' => [
            'view_users',
            'approve_id',
            'manage_listings',
            'view_orders',
        ],
        'support' => [
            'view_users',
            'view_orders',
            'update_orders',
            'access_messages',
        ],
        'user' => [],
    ];
}

function current_user_can(string $permission): bool
{
    $roles = current_user_roles();
    $permissions = rbac_permission_map();

    foreach ($roles as $role) {
        if (in_array($permission, $permissions[$role] ?? [], true)) {
            return true;
        }
    }

    return false;
}

function requirePermission(string $permission, string $message = 'Access denied.'): void
{
    if (!current_user_can($permission)) {
        http_response_code(403);
        die($message);
    }
}
