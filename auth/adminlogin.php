<?php
session_start();

function load_env_value($key, $default = '') {
    $envFile = __DIR__ . '/../secret.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }
            list($envKey, $envValue) = explode('=', $line, 2);
            if (trim($envKey) === $key) {
                return trim(trim($envValue), '"\' ');
            }
        }
    }
    return $default;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // First, try DB-backed staff login.
    try {
        // FIXED: Using explicit __DIR__ to prevent 500 errors
        require_once __DIR__ . '/../db.php';
        
        $stmt = $pdo->prepare('SELECT id, full_name, password, id_verified FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $stmtRoles = $pdo->prepare('SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?');
            $stmtRoles->execute([$user['id']]);
            $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);

            if (in_array('admin', $roles, true) || in_array('manager', $roles, true)) {
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['full_name'] ?: 'Administrator';
                $_SESSION['verified'] = (int) ($user['id_verified'] ?? 0);
                $_SESSION['seller_type'] = null;
                $_SESSION['roles'] = $roles;
                $_SESSION['role'] = !empty($roles) ? ($roles[0] ?? 'manager') : 'manager';
                $_SESSION['is_admin'] = in_array('admin', $roles, true);
                $_SESSION['admin_logged_in'] = true; // Safety flag for middleware

                echo '<script>window.location.href = "../admin/dashboard.php";</script>';
                exit;
            }
        }
    } catch (Exception $e) {
        // Fall through to env-based staff login.
    }

    $adminEmail = trim(load_env_value('ADMIN_EMAIL'));
    $adminPassword = trim(load_env_value('ADMIN_PASSWORD'));

    if ($adminEmail !== '' && strcasecmp($adminEmail, $email) === 0 && $password === $adminPassword) {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'Administrator';
        $_SESSION['verified'] = 1;
        $_SESSION['seller_type'] = null;
        $_SESSION['roles'] = ['admin'];
        $_SESSION['role'] = 'admin';
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_logged_in'] = true; // Safety flag for middleware

        echo '<script>window.location.href = "../admin/dashboard.php";</script>';
        exit;
    }

    // Support via env
    $managerEmail = trim(load_env_value('MANAGER_EMAIL'));
    $managerPassword = trim(load_env_value('MANAGER_PASSWORD'));
    
    if ($managerEmail !== '' && strcasecmp($managerEmail, $email) === 0 && $password === $managerPassword) {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'Support';
        $_SESSION['verified'] = 1;
        $_SESSION['seller_type'] = null;
        $_SESSION['roles'] = ['manager'];
        $_SESSION['role'] = 'manager';
        $_SESSION['is_admin'] = false;
        $_SESSION['admin_logged_in'] = true; // Safety flag for middleware

        echo '<script>window.location.href = "../admin/dashboard.php";</script>';
        exit;
    }

    $error = 'Invalid staff email or password. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Sign In - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: linear-gradient(180deg, #f3f8f9, #eef4f6); }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: 100%; max-width: 420px; background: #fff; border-radius: 16px; box-shadow: 0 12px 40px rgba(0,0,0,.10); padding: 34px; }
        h1 { margin: 0 0 8px; color: #097c87; text-align: center; }
        .sub { margin: 0 0 24px; color: #555; text-align: center; }
        .notice { background: #e3f2fd; color: #0b5b66; padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 14px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 14px; }
        .group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input { width: 100%; padding: 12px 14px; border: 1.5px solid #d8e1e4; border-radius: 10px; font-size: 15px; }
        input:focus { outline: none; border-color: #097c87; }
        .btn { width: 100%; border: 0; background: #097c87; color: #fff; padding: 13px 16px; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; }
        .btn:hover { background: #075e68; }
        .back { margin-top: 14px; text-align: center; font-size: 14px; }
        .back a { color: #097c87; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Staff Sign In</h1>
            <p class="sub">Use your staff email and password</p>

            <div class="notice">This page is for staff access only.</div>

            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="group">
                    <label for="email">Staff Email</label>
                    <input id="email" type="email" name="email" required placeholder="staff@example.com" autocomplete="username">
                </div>
                <div class="group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required placeholder="Enter staff password" autocomplete="current-password">
                </div>
                <button class="btn" type="submit">Sign In</button>
            </form>

            <p class="back"><a href="login.php">Back to user sign in</a></p>
        </div>
    </div>
</body>
</html>
