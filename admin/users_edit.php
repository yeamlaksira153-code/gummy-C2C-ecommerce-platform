<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

auth_sync_current_user($pdo);
requirePermission('view_users');

$userId = (int) ($_GET['id'] ?? 0);
if ($userId <= 0) {
    header('Location: users.php');
    exit;
}


$stmt = $pdo->prepare("SELECT u.id, u.email, u.full_name, u.phone, u.id_number, u.id_verified, u.id_document, u.seller_type, u.role,
    COALESCE(GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', '), 'user') AS roles
    FROM users u
    LEFT JOIN user_roles ur ON ur.user_id = u.id
    LEFT JOIN roles r ON r.id = ur.role_id
    WHERE u.id = ?
    GROUP BY u.id, u.email, u.full_name, u.phone, u.id_number, u.id_verified, u.id_document, u.seller_type, u.role
    LIMIT 1");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: users.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['approve_id'])) {
            requirePermission('approve_id');

            $approve = $pdo->prepare('UPDATE users SET id_verified = 1 WHERE id = ? AND id_document IS NOT NULL AND id_document <> ""');
            $approve->execute([$userId]);
            if ($approve->rowCount() === 0) {
                throw new RuntimeException('Unable to approve this ID.');
            }
            $success = 'ID verification approved.';
        } elseif (current_user_can('update_users')) {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $phone = trim($_POST['phone'] ?? '');
            $sellerType = trim($_POST['seller_type'] ?? 'casual');
            $idVerified = !empty($_POST['id_verified']) ? 1 : 0;

            if ($fullName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Name and email are required.');
            }

            if (!in_array($sellerType, ['casual', 'informal'], true)) {
                $sellerType = 'casual';
            }

            if (!current_user_has_role('admin')) {
                $idVerified = (int) $user['id_verified'];
            }

            //  save both 'seller_type' and 'role' for consistency
            $update = $pdo->prepare('UPDATE users SET full_name = ?, email = ?, phone = ?, seller_type = ?, role = ?, id_verified = ? WHERE id = ?');
            $update->execute([$fullName, $email, $phone, $sellerType, $sellerType, $idVerified, $userId]);
            $success = 'User updated successfully.';
        } else {
            throw new RuntimeException('Access denied.');
        }

        $stmt->execute([$userId]);
        $user = $stmt->fetch();
    } catch (Throwable $throwable) {
        $error = $throwable->getMessage();
    }
}

$pageTitle = 'Edit User';
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/sidebar.php';
?>
<main class="admin-main">
    <section class="admin-panel">
        <div class="page-header">
            <h1>User details</h1>
        </div>

        <div class="content-wrap">
            <?php if ($error): ?>
                <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="notice"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="card">
                <form method="post" class="grid" style="gap:14px;">
                    <div class="form-row">
                        <div>
                            <label>Full name</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" <?php echo current_user_can('update_users') ? '' : 'disabled'; ?> required>
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" <?php echo current_user_can('update_users') ? '' : 'disabled'; ?> required>
                        </div>
                        <div>
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" <?php echo current_user_can('update_users') ? '' : 'disabled'; ?>>
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <label>Seller type</label>
                            <select name="seller_type" <?php echo current_user_can('update_users') ? '' : 'disabled'; ?>>
                                <option value="casual" <?php echo (($user['role'] ?? $user['seller_type']) === 'casual') ? 'selected' : ''; ?>>Casual</option>
                                <option value="informal" <?php echo (($user['role'] ?? $user['seller_type']) === 'informal') ? 'selected' : ''; ?>>Informal</option>
                            </select>
                        </div>
                        <div>
                            <label>ID verified</label>
                            <?php if (current_user_has_role('admin')): ?>
                                <label style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                                    <input type="checkbox" name="id_verified" value="1" <?php echo !empty($user['id_verified']) ? 'checked' : ''; ?>>
                                    <span>Verified</span>
                                </label>
                            <?php else: ?>
                                <input type="text" value="<?php echo !empty($user['id_verified']) ? 'Verified' : 'Pending'; ?>" disabled>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label>Action</label>
                            <?php if (!empty($user['id_document']) && empty($user['id_verified']) && current_user_can('approve_id')): ?>
                                <button type="submit" name="approve_id" value="1" style="margin-top:10px;">Approve ID</button>
                            <?php else: ?>
                                <input type="text" value="No action available" disabled>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (current_user_can('update_users')): ?>
                        <div class="actions">
                            <button type="submit" name="save_user" value="1">Save changes</button>
                            <a class="btn secondary" href="users.php">Back</a>
                        </div>
                    <?php else: ?>
                        <div class="actions">
                            <a class="btn secondary" href="users.php">Back</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php if (!empty($user['id_document'])): ?>
            <div class="card" style="margin-top:20px;">
                <label>Verification Document</label>
                <div style="margin-top:10px;">
                    <a href="../<?php echo htmlspecialchars($user['id_document']); ?>" target="_blank" class="btn secondary">View Uploaded ID File</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php include __DIR__ . '/components/footer.php'; ?>
