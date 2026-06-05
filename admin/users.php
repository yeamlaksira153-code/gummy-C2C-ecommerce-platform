<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

auth_sync_current_user($pdo);
requirePermission('view_users');

function ensure_rbac_defaults(PDO $pdo): void
{
    $pdo->exec("INSERT IGNORE INTO roles (name, description) VALUES
        ('admin', 'Administrator with full access'),
        ('manager', 'Manager with limited admin access'),
        ('user', 'Regular user')");

    $pdo->exec("INSERT IGNORE INTO user_roles (user_id, role_id)
        SELECT u.id, r.id
        FROM users u
        INNER JOIN roles r ON r.name = 'user'
        LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.role_id = r.id
        WHERE ur.user_id IS NULL");
}

function role_id_by_name(PDO $pdo, string $roleName): int
{
    $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = ? LIMIT 1');
    $stmt->execute([$roleName]);

    return (int) ($stmt->fetchColumn() ?: 0);
}

function assign_primary_role(PDO $pdo, int $userId, string $roleName): void
{
    $targetRoleId = role_id_by_name($pdo, $roleName);
    if ($targetRoleId <= 0) {
        throw new RuntimeException('Unknown role: ' . $roleName);
    }

    $roleIdsToClear = [];
    foreach (['admin', 'manager', 'user'] as $role) {
        $roleId = role_id_by_name($pdo, $role);
        if ($roleId > 0) {
            $roleIdsToClear[] = $roleId;
        }
    }

    if (!empty($roleIdsToClear)) {
        $placeholders = implode(',', array_fill(0, count($roleIdsToClear), '?'));
        $stmt = $pdo->prepare('DELETE FROM user_roles WHERE user_id = ? AND role_id IN (' . $placeholders . ')');
        $stmt->execute(array_merge([$userId], $roleIdsToClear));
    }

    $insert = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
    $insert->execute([$userId, $targetRoleId]);
}

function fetch_all_users(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT u.id, u.email, u.full_name, u.phone, u.id_number, u.id_verified, u.id_document, u.seller_type,
        COALESCE(GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', '), 'user') AS roles
        FROM users u
        LEFT JOIN user_roles ur ON ur.user_id = u.id
        LEFT JOIN roles r ON r.id = ur.role_id
        GROUP BY u.id, u.email, u.full_name, u.phone, u.id_number, u.id_verified, u.id_document, u.seller_type
        ORDER BY u.created_at DESC");

    return $stmt->fetchAll();
}

ensure_rbac_defaults($pdo);
auth_sync_current_user($pdo);

$message = '';
$messageType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '';
    $messageType = 'success';

    try {
        // --- ACTION 1: CREATE USER ---
        if (isset($_POST['create_user'])) {
            requirePermission('create_users');

            $fullName = trim($_POST['full_name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $sellerType = trim($_POST['seller_type'] ?? 'casual');

            if ($fullName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Please provide a valid name and email.');
            }

            if (!in_array($sellerType, ['casual', 'informal'], true)) {
                $sellerType = 'casual';
            }

            if ($password === '') {
                $password = 'Temp' . bin2hex(random_bytes(4)) . '!';
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            // Ensure 'role' and 'seller_type' are both selected
$stmt = $pdo->query("SELECT id, full_name, email, role, seller_type, id_verified, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();



            $message = "User created successfully as " . ucfirst($sellerType);

        // --- ACTION 2: APPROVE ID ---
        } elseif (isset($_POST['approve_id'])) {
            requirePermission('approve_id');

            $userId = (int) ($_POST['user_id'] ?? 0);
            $approve = $pdo->prepare('UPDATE users SET id_verified = 1 WHERE id = ? AND id_document IS NOT NULL AND id_document <> ""');
            $approve->execute([$userId]);
            
            if ($approve->rowCount() === 0) {
                throw new RuntimeException('Unable to approve this ID. Make sure a document is uploaded.');
            }
            $message = 'ID verification approved.';

        // --- ACTION 3: UPDATE USER TYPE (ROLE) ---
        } elseif (isset($_POST['update_role'])) {
            requirePermission('assign_roles');

            $userId = (int) ($_POST['user_id'] ?? 0);
            $roleName = trim($_POST['role_name'] ?? 'casual');

            if ($userId <= 0) {
                throw new RuntimeException('Invalid user.');
            }

            if (!empty($_SESSION['user_id']) && (int) $_SESSION['user_id'] === $userId) {
                throw new RuntimeException('You cannot change your own role from this page.');
            }

            // Simplified: Update the role column directly
            $update = $pdo->prepare('UPDATE users SET role = ?, seller_type = ? WHERE id = ?');
            $update->execute([$roleName, $roleName, $userId]);
            
            $message = 'User type updated to ' . ucfirst($roleName);

        // --- ACTION 4: DELETE USER ---
        } elseif (isset($_POST['delete_user'])) {
            requirePermission('delete_users');

            $userId = (int) ($_POST['user_id'] ?? 0);
            if (!empty($_SESSION['user_id']) && (int) $_SESSION['user_id'] === $userId) {
                throw new RuntimeException('You cannot delete your own account.');
            }

            $delete = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $delete->execute([$userId]);
            
            if ($delete->rowCount() === 0) {
                throw new RuntimeException('User not found.');
            }

            $message = 'User deleted successfully.';
        }

        // Sync session data if you modified yourself (security check)
        auth_sync_current_user($pdo);

    } catch (Throwable $throwable) {
        $message = $throwable->getMessage();
        $messageType = 'error';
    }
}


$users = fetch_all_users($pdo);
$pageTitle = 'Users';
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/sidebar.php';
?>
<main class="admin-main">
    <section class="admin-panel">
        <div class="page-header">
            <h1>Users</h1>
        </div>

        <div class="content-wrap">
            <?php if ($message): ?>
                <div class="notice <?php echo $messageType === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <?php if (current_user_can('create_users')): ?>
                <div class="card">
                    <h2 style="margin-top:0;">Create user</h2>
                    <form method="post" class="grid" style="gap:14px;">
                        <div class="form-row">
                            <div>
                                <label>Full name</label>
                                <input type="text" name="full_name" required>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" required>
                            </div>
                            <div>
                                <label>Phone</label>
                                <input type="text" name="phone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div>
                                <label>Password</label>
                                <input type="text" name="password" placeholder="Leave blank for temporary password">
                            </div>
                           
                            <div>
                                <label>Seller type</label>
                                <select name="seller_type">
                                    <option value="casual">Casual</option>
                                    <option value="informal">Informal</option>
                                </select>
                            </div>
                        </div>
                        <div class="actions">
                            <button type="submit" name="create_user" value="1">Create user</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2 style="margin-top:0;">All users</h2>
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Seller type</th>
                                <th>ID verified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo (int) $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><span class="badge"><?php echo htmlspecialchars($user['roles']); ?></span></td>
                                  <td>
    <?php 
    // This logic forcedly checks the new 'role' column first
    $type = !empty($user['role']) ? $user['role'] : ($user['seller_type'] ?? 'casual');
    $isInformal = (strtolower(trim($type)) === 'informal');
    ?>
    <span class="badge" style="background: <?= $isInformal ? '#e8f4f8' : '#eee' ?>; color: <?= $isInformal ? '#097c87' : '#666' ?>;">
        <?= ucfirst($type) ?>
    </span>
</td>

                                    <td>
                                        <?php if (!empty($user['id_verified'])): ?>
                                            <span class="badge status-ok">Verified</span>
                                        <?php elseif (!empty($user['id_document'])): ?>
                                            <span class="badge status-warn">Pending</span>
                                        <?php else: ?>
                                            <span class="badge status-muted">Not uploaded</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a class="btn secondary" href="users_edit.php?id=<?php echo (int) $user['id']; ?>">View / Edit</a>
                                            <?php if (current_user_can('approve_id') && empty($user['id_verified']) && !empty($user['id_document'])): ?>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                                    <button type="submit" name="approve_id" value="1">Approve ID</button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if (current_user_can('assign_roles')): ?>
                                                <form method="post" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                                    <input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                                   
                                                </form>
                                            <?php endif; ?>
                                            <?php if (current_user_can('delete_users') && (!isset($_SESSION['user_id']) || (int) $_SESSION['user_id'] !== (int) $user['id'])): ?>
                                                <form method="post" onsubmit="return confirm('Delete this user?');">
                                                    <input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                                    <button class="btn danger" type="submit" name="delete_user" value="1">Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
</div>
</body>
</html>
