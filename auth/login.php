<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - GUMMY Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #097c87;
            padding: 10px 20px;
            flex-wrap: wrap;
            position: relative;
            gap: 15px;
        }

        .logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .logo-img {
            max-height: 60px;
            width: auto;
            display: block;
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }

        #searchBar {
            width: 100%;
            min-height: 38px;
            border-radius: 20px;
            padding: 0 15px;
            background-color: #ffffff;
            border: none;
            font-size: 14px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-signup {
            background: white;
            color: #097c87;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* MAIN CONTENT */
        .main-content {
            max-width: 450px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        h1 {
            color: #097c87;
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #097c87;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: #097c87;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #075e68;
        }

        .forgot-link {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-link a {
            color: #097c87;
            font-size: 14px;
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
            color: #666;
        }

        .signup-link a {
            color: #097c87;
            font-weight: 500;
        }

        .demo-notice {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            color: #097c87;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #097c87;
            color: white;
            margin-top: 40px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .search-container {
                order: 3;
                max-width: 100%;
                margin: 10px 0;
            }
            
            .nav-links {
                order: 4;
                display: none;
                flex-direction: column;
                width: 100%;
                background: #097c87;
                padding: 15px;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .auth-buttons {
                order: 2;
                margin-left: auto;
            }
            
            .menu-toggle {
                display: block;
                order: 1;
            }
            
            .logo {
                order: 0;
            }
            
            .main-content {
                margin: 20px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <a href="../index.php">
            <img src="../../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
        </a>
    </div>

    <div class="search-container">
        <input id="searchBar" placeholder="Search item..." type="text" />
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">&#9776;</button>

    <nav>
        <div class="nav-links" id="navLinks">
          <a href="../index.php">Home</a>
          <a href="../casualtraders.php">Casual</a>
          <a href="../informaltraders.php">Informal</a>
          <a href="../sell.php">Sell Item</a>
          <a href="../mylistings.php">My Listings</a>
          <a href="../messages.php">Messages</a>
          <a href="../profile.php">Profile</a>
          <a href="register.php" class="btn-signup" style="background: white; color: #097c87; padding: 8px 20px; border-radius: 20px; font-weight: 500; text-decoration: none;">Sign Up</a>
      </div>
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <h1>Welcome Back</h1>
    <p class="subtitle">Sign in to your account</p>
    
    <?php
    $error = '';
    // Are we in admin login mode? (link adds ?admin=1)
    $admin_mode = !empty($_GET['admin']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Include database connection
        require_once '../db.php';
        // honor admin_mode if posted (form includes hidden input when link used)
        $admin_mode = !empty($_POST['admin_mode']);

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['verified'] = $user['id_verified'];
            $_SESSION['seller_type'] = $user['seller_type'];
            // Load roles for RBAC
            try {
                $stmtRoles = $pdo->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id=ur.role_id WHERE ur.user_id = ?");
                $stmtRoles->execute([$user['id']]);
                $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $roles = [];
            }

            $_SESSION['roles'] = $roles;
            $_SESSION['role'] = !empty($roles) ? ($roles[0] ?? 'user') : 'user';
            $_SESSION['is_admin'] = in_array('admin', $roles, true);

            // If user requested admin login, require admin role and redirect there
            if ($admin_mode) {
                if (in_array('admin', $roles, true) || in_array('manager', $roles, true)) {
                    echo '<script>window.location.href = "../admin/index.php";</script>';
                    exit;
                } else {
                    // Not an admin/manager user
                    $error = 'Access denied. Please sign in with a staff account.';
                    // clear admin flag
                    $_SESSION['is_admin'] = false;
                }
            }

            // Default redirect
            echo '<script>window.location.href = "../index.php";</script>';
            exit;
        } else {
            // If admin-mode requested, allow fallback auth using env vars
            if (!empty($admin_mode)) {
                $env_admin = trim(getenv('ADMIN_EMAIL') ?: '');
                $env_pass = trim(getenv('ADMIN_PASSWORD') ?: '');
                if ($env_admin !== '' && strcasecmp($env_admin, $email) === 0 && $password === $env_pass) {
                    // Grant admin session (no DB user required)
                    $_SESSION['user_id'] = 0;
                    $_SESSION['user_name'] = 'Administrator';
                    $_SESSION['verified'] = 1;
                    $_SESSION['seller_type'] = null;
                    $_SESSION['roles'] = ['admin'];
                    $_SESSION['role'] = 'admin';
                    $_SESSION['is_admin'] = true;
                    echo '<script>window.location.href = "../admin/index.php";</script>';
                    exit;
                }
                $error = 'Invalid staff email or password. Please try again.';
            } else {
                $error = 'Invalid email or password. Please try again.';
            }
        }
    }
    ?>
    
    <?php if ($error): ?>
        <div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
   
    
    <form method="POST">
        <?php if (!empty($admin_mode)): ?>
            <input type="hidden" name="admin_mode" value="1">
            <div style="margin-bottom:12px;color:#097c87;font-weight:600">Staff sign in — use your staff email and password</div>
        <?php endif; ?>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="your@email.com" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        
        <button type="submit" class="submit-btn">Sign In</button>
    </form>
    <?php if (empty($admin_mode)): ?>
        <p style="text-align:center;margin-top:12px;font-size:14px"><a href="adminlogin.php">Staff sign in</a></p>
    <?php endif; ?>
    <p class="forgot-link">
        <a href="#">Forgot your password?</a>
    </p>
    
    <p class="signup-link">
        Don't have an account? <a href="register.php">Sign Up</a>
    </p>
</div>

<div class="footer">
    <p>&copy; 2026 GUMMY | Trading</p>
</div>

<script>
function toggleMenu() {
    var navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('active');
}
</script>

</body>
</html>
