<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GUMMY Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .navbar {
            display: flex;
            align-items: center;
            background-color: #097c87;
            padding: 15px 20px;
            color: white;
            flex-wrap: wrap;
            position: relative;
            gap: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
            flex-wrap: wrap;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .nav-links a:hover {
            color: #ddd;
        }
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 5px 10px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        h1 {
            color: #097c87;
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background: #097c87;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
        }
        .profile-header h2 {
            color: #333;
            margin: 0;
        }
        .verified-badge {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin-top: 10px;
        }
        .unverified-badge {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin-top: 10px;
        }
        .form-section {
            margin-bottom: 25px;
        }
        .form-section h3 {
            color: #097c87;
            border-bottom: 2px solid #097c87;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #097c87;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            transition: background 0.3s;
        }
        .submit-btn:hover {
            background: #00bcd4;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
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
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .login-link {
            text-align: center;
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
            color: #666;
        }
        .login-link a {
            color: #097c87;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background: #097c87;
            color: white;
            margin-top: 40px;
        }
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
                margin-left: auto;
            }
            .nav-links {
                display: none;
                width: 100%;
                background-color: #097c87;
                text-align: center;
                padding: 15px 0;
                flex-direction: column;
            }
            .nav-links.active {
                display: flex;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <style>
        .navbar {
            display: flex;
            align-items: center;
            background: #097c87;
            padding: 10px 20px;
            flex-wrap: wrap;
            position: relative;
            gap: 15px;
            width: 100%;
        }
        .logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            margin-right: 0;
            font-size: inherit;
            font-weight: inherit;
        }
        .logo-img {
            max-height: 60px;
            width: auto;
            display: block;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-left: auto;
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
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 5px 10px;
        }
        @media (max-width: 900px) {
            .nav-links {
                order: 4;
                display: none;
                flex-direction: column;
                width: 100%;
                background: #097c87;
                padding: 15px;
                margin-left: 0;
            }
            .nav-links.active {
                display: flex;
            }
            .nav-links a {
                width: 100%;
                display: block;
                text-align: center;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                border-radius: 0;
            }
            .menu-toggle {
                display: block;
                order: 2;
                margin-left: auto;
            }
            .logo {
                order: 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
        <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')">☰</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="casualtraders.php">Browse</a>
            <a href="informaltraders.php">Traders</a>
            <a href="sell.php">Sell Item</a>
            <a href="messages.php">Messages</a>
            <a href="trackorder.php">Track Order</a>
            <a href="mylistings.php">My Listings</a>
            <a href="profile.php">Profile</a>
            <a href="auth/register.php" style="background: white; color: #097c87; padding: 8px 20px; border-radius: 20px; font-weight: 500; text-decoration: none;">Sign Up</a>
        </div>
    </nav>

    <div class="container">
        <h1>My Profile</h1>
        
        <?php
        // Include database connection
        require_once 'db.php';
        
        $message = '';
        $messageType = '';
        $user = null;
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Not logged in, show login prompt
            echo '<div class="profile-card">';
            echo '<div class="profile-header">';
            echo '<div class="avatar">👤</div>';
            echo '<h2>Please Sign In</h2>';
            echo '<p>You need to sign in to view your profile.</p>';
            echo '</div>';
            echo '<p class="login-link">Don\'t have an account? <a href="auth/register.php">Sign Up</a></p>';
            echo '<p class="login-link">Already have an account? <a href="auth/login.php">Sign In</a></p>';
            echo '</div>';
        } else {
            // Load the current user before handling POST actions so the upload section can reflect the latest state.
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            // Handle profile update
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['upload_id'])) {
                    if (!empty($_FILES['id_document']['name']) && $_FILES['id_document']['error'] === UPLOAD_ERR_OK) {
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                        $uploadDir = 'ids/';

                        $ext = strtolower(pathinfo($_FILES['id_document']['name'], PATHINFO_EXTENSION));

                        if (!in_array($ext, $allowedExtensions, true)) {
                            $message = 'Please upload a JPG, PNG, or PDF file.';
                            $messageType = 'error';
                        } else {
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }

                            $newName = uniqid() . '_id.' . $ext;
                            $targetPath = $uploadDir . $newName;

                            if (move_uploaded_file($_FILES['id_document']['tmp_name'], $targetPath)) {
                                try {
                                    $stmt = $pdo->prepare("UPDATE users SET id_document = ?, id_verified = 0 WHERE id = ?");
                                    $stmt->execute([
                                        'ids/' . $newName,
                                        $_SESSION['user_id']
                                    ]);

                                    $user['id_document'] = 'ids/' . $newName;
                                    $user['id_verified'] = 0;
                                    $message = 'ID uploaded successfully. Waiting for approval.';
                                    $messageType = 'success';
                                } catch (PDOException $e) {
                                    $message = 'Error saving ID upload: ' . $e->getMessage();
                                    $messageType = 'error';
                                }
                            } else {
                                $message = 'Error uploading ID document.';
                                $messageType = 'error';
                            }
                        }
                    } else {
                        $message = 'Please choose an ID document to upload.';
                        $messageType = 'error';
                    }
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, id_number = ? WHERE id = ?");
                        $stmt->execute([
                            $_POST['full_name'] ?? '',
                            $_POST['phone'] ?? '',
                            $_POST['id_number'] ?? '',
                            $_SESSION['user_id']
                        ]);
                        
                        // Update session name
                        $_SESSION['user_name'] = $_POST['full_name'] ?? '';
                        
                        $message = 'Profile updated successfully!';
                        $messageType = 'success';
                    } catch (PDOException $e) {
                        $message = 'Error updating profile: ' . $e->getMessage();
                        $messageType = 'error';
                    }
                }
            }
            
            if ($message):
        ?>
        <div id="profileAlert" class="alert alert-<?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="profile-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="profile-header">
                    <div class="avatar">👤</div>
                    <h2><?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?></h2>
                    <?php if ($user['id_verified']): ?>
                    <span class="verified-badge">Verified</span>
                    <?php elseif (!empty($user['id_document'])): ?>
                    <span class="unverified-badge"> Pending admin approval</span>
                    <?php else: ?>
                    <span class="unverified-badge"> Unverified</span>
                    <?php endif; ?>
        
                </div>
                
                <div class="form-section">
                    <h3>Personal Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>ID Number</label>
                            <input type="text" name="id_number" value="<?php echo htmlspecialchars($user['id_number'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <?php if (!empty($user['id_document'])): ?>
                    <?php else: ?>
                        <div class="form-group">
                            <label>Upload ID Document</label>
                            <input type="file" name="id_document" accept="image/*,application/pdf">
                        </div>
                        <button type="submit" name="upload_id" value="1" class="submit-btn" style="margin-bottom: 20px;">Upload ID</button>
                    <?php endif; ?>
                </div>
               

                <button type="submit" class="submit-btn">Update Profile</button>
                 <div style="text-align: right; margin-top: 15px;">
    <a href="auth/logout.php" style="
        font-size: 12px; 
        color: #888; 
        text-decoration: none; 
        border: 1px solid #ddd; 
        padding: 4px 10px; 
        border-radius: 4px;
        transition: all 0.2s;
    " onmouseover="this.style.color='#d9534f'; this.style.borderColor='#d9534f';" 
       onmouseout="this.style.color='#888'; this.style.borderColor='#ddd';">
        Sign Out
    </a>
</div>
            </form>
        </div>
        
        <?php } ?>
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>

    <script>
    function toggleMenu() {
        var navLinks = document.getElementById('navLinks');
        navLinks.classList.toggle('active');
    }

    <?php if ($messageType === 'success'): ?>
    setTimeout(function () {
        var alertBox = document.getElementById('profileAlert');
        if (alertBox) {
            alertBox.style.display = 'none';
        }
    }, 2000);
    <?php endif; ?>
    </script>
</body>
</html>
