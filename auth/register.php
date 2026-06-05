<?php
session_start();
require_once '../db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Setup variables
    $fullName = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $idNumber = trim($_POST['id_number'] ?? '');
    $role = $_POST['role'] ?? 'casual'; 

    // 2. Simple Validation
    if (empty($fullName) || empty($email) || empty($password)) {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 3. Single Unified Insert
        try {
            // Fill both 'role' and 'seller_type' to ensure Admin Panel consistency
            $stmt = $pdo->prepare("INSERT INTO users (
                email, password, full_name, phone, id_number, 
                id_verified, seller_type, role
            ) VALUES (?, ?, ?, ?, ?, 0, ?, ?)");
            
            $stmt->execute([
                $email,
                $hashedPassword,
                $fullName,
                $phone,
                $idNumber,
                $role, // seller_type
                $role  // role
            ]);
            
            $lastId = $pdo->lastInsertId();

            // 4. Assign system role (RBAC) if tables exist
            try {
                $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'user' LIMIT 1");
                $roleStmt->execute();
                $roleId = $roleStmt->fetchColumn();
                if ($roleId) {
                    $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$lastId, $roleId]);
                }
            } catch (Exception $e) { /* Ignore RBAC errors */ }

            $message = 'Registration successful! Redirecting to login...';
            $messageType = 'success';
            echo '<script>setTimeout(function(){ window.location.href = "login.php"; }, 3000);</script>';

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = 'Error: Email already exists.';
            } else {
                $message = 'Registration failed: ' . $e->getMessage();
            }
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .navbar { display: flex; align-items: center; background-color: #097c87; padding: 10px 20px; gap: 15px; }
        .logo-img { max-height: 50px; width: auto; }
        .nav-links { display: flex; align-items: center; gap: 15px; margin-left: auto; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; padding: 8px 15px; border-radius: 20px; }
        
        .main-content { max-width: 600px; margin: 40px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #097c87; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        .submit-btn { width: 100%; padding: 15px; background: #097c87; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #097c87; font-weight: bold; text-decoration: none; }
        
        @media (max-width: 768px) { .form-row { flex-direction: column; } .main-content { margin: 20px; padding: 20px; } }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <a href="../index.php"><img src="../../images/logo.png" alt="GUMMY" class="logo-img" /></a>
    </div>
    <div class="nav-links">
        <a href="login.php">Sign In</a>
    </div>
</div>

<div class="main-content">
    <h1>Create Account</h1>
    <p class="subtitle">Join GUMMY Marketplace today</p>

    <?php if ($message): ?>
        <div class="alert" style="background: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="full_name" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Phone Number *</label>
            <input type="tel" name="phone" placeholder="Your phone number" required>
        </div>

        <div class="form-group">
            <label>Account Type</label>
            <select name="role" id="roleSelect" required>
                <option value="casual">Casual Seller (Personal)</option>
                <option value="informal">Informal Trader (Business)</option>
            </select>
        </div>

        <div class="form-group">
            <label>South African ID Number</label>
            <input type="text" name="id_number" placeholder="Enter 13-digit ID" pattern="\d{13}">
        </div>
             
        <button type="submit" class="submit-btn">Create Account</button>
    </form>
    
    <p class="login-link">Already have an account? <a href="login.php">Sign In</a></p>
</div>

</body>
</html>
