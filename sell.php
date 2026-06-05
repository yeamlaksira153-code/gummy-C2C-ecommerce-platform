<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Item - GUMMY Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        /* NAVBAR */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #097c87;
            padding: 15px 30px;
            color: white;
            position: relative;
            flex-wrap: wrap;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .search-container {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
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
        }
        #searchBar {
            width: 100%;
            min-height: 38px;
            border-radius: 20px;
            padding: 0 15px;
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
            border: none;
            font-size: 14px;
        }
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-signin {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-signin:hover {
            background: white;
            color: #097c87;
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
            text-decoration: none;
        }
        .btn-signup:hover {
            background: #f0f0f0;
        }
        .nav-links {
            display: flex;
            gap: 20px;
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
        }

        /* MAIN CONTENT */
        .main-content {
            padding: 40px 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* SELLER TYPE SELECTION */
        .seller-type-selection {
            text-align: center;
            padding: 40px 20px;
        }
        .seller-type-selection h1 {
            color: #097c87;
            margin-bottom: 10px;
        }
        .seller-type-selection > p {
            color: #333;
            margin-bottom: 40px;
            font-size: 16px;
        }
        .seller-types {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .seller-type-card {
            background: white;
            border-radius: 15px;
            padding: 35px 25px;
            width: 280px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 3px solid #097c87;
        }
        .seller-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            background: #f0f9fa;
        }
        .seller-type-card .icon {
            font-size: 55px;
            margin-bottom: 15px;
        }
        .seller-type-card h2 {
            margin: 0 0 10px 0;
            color: #097c87;
            font-size: 20px;
        }
        .seller-type-card p {
            color: #333;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }

        /* FORMS */
        .form-container {
            display: none;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .form-container.active {
            display: block;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            color: #097c87;
            margin: 0 0 10px 0;
        }
        .form-header p {
            color: #333;
            margin: 0;
        }

        .form-section-title {
            color: #097c87;
            font-size: 16px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #097c87;
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
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, 
        .form-group textarea:focus, 
        .form-group select:focus {
            outline: none;
            border-color: #097c87;
        }

        /* IMAGE UPLOAD */
        .image-upload-area {
            border: 3px dashed #097c87;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f0f9fa;
        }
        .image-upload-area:hover {
            background: #e0f4f5;
        }
        .image-upload-area .icon {
            font-size: 50px;
            color: #097c87;
            margin-bottom: 15px;
        }
        .image-upload-area p {
            color: #333;
            margin: 0 0 10px 0;
        }
        .image-upload-area span {
            color: #097c87;
            font-weight: 500;
        }
        .image-upload-area input[type="file"] {
            display: none;
        }
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .image-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #097c87;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }

        /* SUBMIT BUTTON */
        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            background: #097c87;
            color: white;
        }
        .submit-btn:hover {
            background: #075e68;
        }

        /* BACK BUTTON */
        .back-btn {
            background: none;
            border: none;
            color: #097c87;
            cursor: pointer;
            font-size: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-btn:hover {
            color: #075e68;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
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
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #097c87;
                padding: 15px;
                z-index: 1000;
            }
            .nav-links.active {
                display: flex;
            }
            .menu-toggle {
                display: block;
            }
            .nav-links a {
                padding: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .seller-types {
                flex-direction: column;
                align-items: center;
            }
            .seller-type-card {
                width: 100%;
                max-width: 350px;
            }
            
            .form-container {
                padding: 25px 20px;
            }
            
            .image-preview-item {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 20px 15px;
            }
            .seller-type-selection h1 {
                font-size: 24px;
            }
            .form-header h2 {
                font-size: 22px;
            }
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #097c87;
            color: white;
            margin-top: 40px;
        }

        /* ALERT */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
        <div class="search-container">
            <input id="searchBar" placeholder="Search items..." type="text">
        </div>
        <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')">&#9776;</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="casualtraders.php">Casual</a>
            <a href="informaltraders.php">Informal</a>
            <a href="sell.php">Sell Item</a>
            <a href="mylistings.php">My Listings</a>
            <a href="messages.php">Messages</a>
            <a href="trackorder.php">Track Order</a>
            <a href="profile.php">Profile</a>
            <a href="auth/register.php" class="btn-signup" style="background: white; color: #097c87; padding: 8px 20px; border-radius: 20px; font-weight: 500; text-decoration: none;">Sign Up</a>
            
        </div>
    </nav>
    <?php
session_start();
require_once 'db.php';

// 1. Get current user data
$stmt = $pdo->prepare("SELECT role, id_verified FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// 2. Apply your business logic
if ($user['role'] === 'informal' && $user['id_verified'] == 0) {
    // Block unverified informal traders
    echo "<div class='alert' style='padding: 20px; background: #fff3cd; color: #856404; text-align: center;'>";
    echo "<h2>Verification Required</h2>";
    echo "<p>Informal Traders must complete an ID check before listing items.</p>";
    echo "<a href='profile.php' class='btn'>Upload ID Now</a>";
    echo "</div>";
    exit; // Stop the page from loading the sell form
}

// Casual sellers and verified traders will see the form below...
?>


    <div class="main-content">
        <?php
        // Handle form submission
        $message = '';
        $messageType = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sellerType = $_POST['seller_type'] ?? '';

            // Get user ID from session
            $userId = $_SESSION['user_id'] ?? 0;

            if ($userId == 0) {
                $message = 'Please sign in to list an item.';
                $messageType = 'error';
            } else {
                // Check seller verification rule
                $userStmt = $pdo->prepare("SELECT id_verified FROM users WHERE id = ?");
                $userStmt->execute([$userId]);
                $user = $userStmt->fetch();

                if ($sellerType === 'informal' && $user['id_verified'] == 0) {
                    $message = 'Informal traders must be verified to list products. Please verify your ID first.';
                    $messageType = 'error';
                } else {
                    // Proceed with listing
                    // Handle image upload
                    $imagePaths = [];
                    if (!empty($_FILES['images']['name'][0])) {
                        $uploadDir = '/listings/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                                $tmpName = $_FILES['images']['tmp_name'][$i];
                                $name = basename($_FILES['images']['name'][$i]);
                                $ext = pathinfo($name, PATHINFO_EXTENSION);
                                $newName = uniqid() . '_' . time() . '.' . $ext;
                                $targetPath = $uploadDir . $newName;

                                if (move_uploaded_file($tmpName, $targetPath)) {
                                    $imagePaths[] = '/listings/' . $newName;
                                }
                            }
                        }
                    }

                    // Prepare listing data
                    $listing = [
                        'id' => uniqid(),
                        'seller_type' => $sellerType,
                        'title' => $_POST['title'] ?? '',
                        'category' => $_POST['category'] ?? '',
                        'price' => $_POST['price'] ?? '',
                        'description' => $_POST['description'] ?? '',
                        'contact' => $_POST['contact'] ?? '',
                        'location' => $_POST['location'] ?? '',
                        'condition' => $_POST['condition'] ?? '',
                        'images' => $imagePaths,
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'active'
                    ];

                    // Add informal trader specific fields
                    if ($sellerType === 'informal') {
                        $listing['trader_name'] = $_POST['trader_name'] ?? '';
                        $listing['id_number'] = $_POST['id_number'] ?? '';
                        $listing['years_experience'] = $_POST['years_experience'] ?? '';
                        $listing['delivery_options'] = $_POST['delivery_options'] ?? '';
                        $listing['warranty'] = $_POST['warranty'] ?? '';
                    }

                    // Add casual seller specific fields
                    if ($sellerType === 'casual') {
                        $listing['negotiable'] = isset($_POST['negotiable']) ? 'yes' : 'no';
                    }

                    try {
                    // Insert listing into database
                    $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, description, price, category, `condition`, seller_type, location, negotiable, trader_name, id_number, years_experience, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                    $stmt->execute([
                        $userId,
                        $_POST['title'] ?? '',
                        $_POST['description'] ?? '',
                        $_POST['price'] ?? 0,
                        $_POST['category'] ?? '',
                        $_POST['condition'] ?? '',
                        $sellerType,
                        $_POST['location'] ?? '',
                        isset($_POST['negotiable']) ? 'yes' : 'no',
                        $_POST['trader_name'] ?? '',
                        $_POST['id_number'] ?? '',
                        $_POST['years_experience'] ?? 0
                    ]);
                    
                    $listingId = $pdo->lastInsertId();
                    
                    // Handle image uploads from base64 data
                    $uploadDir = __DIR__ . '/listings/';
;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $imageData = $_POST['image_data'] ?? '';
                    
                    if (!empty($imageData)) {
                        // Decode base64 image data
                        $imageParts = explode(';base64,', $imageData);
                        if (count($imageParts) == 2) {
                            $imageType = str_replace('data:', '', $imageParts[0]);
                            $imageBase64 = base64_decode($imageParts[1]);
                            
                            // Determine file extension
                            $ext = 'jpg';
                            if (strpos($imageType, 'png') !== false) $ext = 'png';
                            elseif (strpos($imageType, 'gif') !== false) $ext = 'gif';
                            
                            $newName = uniqid() . '.' . $ext;
                            $savedPath = $uploadDir . $newName;
                            
                            if (file_put_contents($savedPath, $imageBase64) !== false) {
                                $imgPath = '/listings/' . $newName;
                                $imgStmt = $pdo->prepare("INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)");
                                $imgStmt->execute([$listingId, $imgPath]);
                            }
                        }
                    } else {
                        // Fallback to regular file upload
                        $files = $_FILES['images'] ?? [];
                    }
                    
                    // Only process $_FILES if using legacy method
                    if (!empty($files) && !empty($files['name'])) {
                        $fileCount = is_array($files['name']) ? count($files['name']) : 1;

                        for ($key = 0; $key < $fileCount; $key++) {
                            $name = is_array($files['name']) ? $files['name'][$key] : $files['name'];
                            $error = is_array($files['error']) ? $files['error'][$key] : $files['error'];
                            $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$key] : $files['tmp_name'];
                            $type = is_array($files['type']) ? $files['type'][$key] : $files['type'];
                            $size = is_array($files['size']) ? $files['size'][$key] : $files['size'];

                            if ($error === UPLOAD_ERR_OK && $name && !empty($type) && strpos($type, 'image/') === 0 && $size > 0) {
                                $ext = pathinfo($name, PATHINFO_EXTENSION);
                                $newName = uniqid() . '_' . $key . '.' . $ext;
                                $savedPath = $uploadDir . $newName;

                                // Use file_get_contents/put_contents instead of move_uploaded_file
                                $imageData = @file_get_contents($tmpName);
                                if ($imageData !== false && file_put_contents($savedPath, $imageData) !== false) {
                                    $imgPath = '/listings/' . $newName;
                                    try {
                                        $imgStmt = $pdo->prepare("INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)");
                                        $imgStmt->execute([$listingId, $imgPath]);
                                    } catch (Exception $e) {
                                        
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($message == '') {
                        $message = 'Your item has been listed successfully!';
                    }
                    $messageType = 'success';
                    } catch (PDOException $e) {
                        $message = 'Error listing item: ' . $e->getMessage();
                        $messageType = 'error';
                    }
                }
            }
        }
        ?>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Seller Type Selection -->
        <div class="seller-type-selection" id="sellerSelection">
            <h1>What type of seller are you?</h1>
            <p>Choose the option that best describes your selling situation</p>
            
            <div class="seller-types">
                <div class="seller-type-card" onclick="selectSellerType('casual')">
                    <h2>Casual Seller</h2>
                    <p>Occasionally selling personal items you no longer need. Quick and simple listing.</p>
                </div>
                
                <div class="seller-type-card" onclick="selectSellerType('informal')">
                    <h2>Informal Trader</h2>
                    <p>Unregistered small-scale seller selling goods for a living. No business registration needed.</p>
                </div>
                

            </div>
        </div>

        <!-- Casual Seller Form -->
        <div class="form-container" id="casualForm">
            <button class="back-btn" onclick="goBack()">Back to seller type selection</button>
            <div class="form-header">
                <h2>List Your Item</h2>
                <p>Casual Seller - Quick & Easy Listing</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data" id="casualListingForm">
                <input type="hidden" name="seller_type" value="casual">
                
                <div class="form-group">
                    <label>Item Title *</label>
                    <input type="text" name="title" placeholder="What are you selling?" required>
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">Select category</option>
                        <option value="home">Home & Garden</option>
                        <option value="clothes">Clothes & Fashion</option>
                        <option value="electronics">Electronics</option>
                        <option value="vehicles">Vehicles</option>
                        <option value="sports">Sports & Outdoors</option>
                        <option value="toys">Toys & Games</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Price (R) *</label>
                    <input type="number" name="price" placeholder="Enter price in Rand" required>
                </div>
                
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" placeholder="Describe your item - condition, features, reason for selling..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition">
                        <option value="new">New</option>
                        <option value="like_new">Like New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" placeholder="City/Area" required>
                </div>
                
                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="tel" name="contact" placeholder="Your phone number" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="negotiable" style="width: auto;"> Price is negotiable
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Item Photos</label>
                    <div class="image-upload-area" onclick="document.getElementById('casualImages').click()">
                        <p>Click to upload images</p>
                        <span>PNG, JPG up to 5MB each</span>
                        <input type="file" id="casualImages" name="images[]" accept="image/*" multiple onchange="previewImages(this, 'casualPreview'); prepareImages(this, 'casualHiddenImages')">
                        <input type="hidden" id="casualHiddenImages" name="image_data" value="">
                    </div>
                    <div class="image-preview" id="casualPreview"></div>
                </div>
                
                <button type="submit" class="submit-btn">Post Item</button>
            </form>
        </div>

        <!-- Informal Trader Form -->
        <div class="form-container" id="informalForm">
            <button class="back-btn" onclick="goBack()">Back to seller type selection</button>
            <div class="form-header">
                <h2>List Your Item</h2>
                <p>Informal Trader - Small-Scale Seller</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data" id="informalListingForm">
                <input type="hidden" name="seller_type" value="informal">
               
                
                <!-- Item Information -->
                <div class="form-section-title">Item Information</div>
                
                <div class="form-group">
                    <label>Item Title *</label>
                    <input type="text" name="title" placeholder="What are you selling?" required>
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">Select category</option>
                        <option value="home">Home & Garden</option>
                        <option value="clothes">Clothes & Fashion</option>
                        <option value="electronics">Electronics</option>
                        <option value="vehicles">Vehicles</option>
                        <option value="food">Food & Beverages</option>
                        <option value="beauty">Beauty & Health</option>
                        <option value="handmade">Handmade Crafts</option>
                        <option value="services">Services</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Price (R) *</label>
                    <input type="number" name="price" placeholder="Enter price in Rand" required>
                </div>
                
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" placeholder="Describe your item, how you use it, quality..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition">
                        <option value="new">New</option>
                        <option value="like_new">Like New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" placeholder="Where are you based?" required>
                </div>
                
                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="tel" name="contact" placeholder="Your phone number" required>
                </div>
                
                <!-- Trading Options -->
                <div class="form-section-title">Trading Options</div>
                
                <div class="form-group">
                    <label>Delivery Options</label>
                    <select name="delivery_options">
                        <option value="pickup">Meetup Only</option>
                        <option value="delivery">Local Delivery Available</option>
                        <option value="both">Meetup & Delivery</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Warranty/ Guarantee</label>
                    <select name="warranty">
                        <option value="none">No Warranty</option>
                        <option value="seller_guarantee">Seller Guarantee</option>
                        <option value="days_7">7 Days</option>
                        <option value="days_14">14 Days</option>
                        <option value="days_30">30 Days</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Product Images</label>
                    <div class="image-upload-area" onclick="document.getElementById('informalImages').click()">
                        <p>Click to upload product images</p>
                        <span>PNG, JPG up to 5MB each</span>
                        <input type="file" id="informalImages" name="images[]" accept="image/*" multiple onchange="previewImages(this, 'informalPreview'); prepareImages(this, 'informalHiddenImages')">
                        <input type="hidden" id="informalHiddenImages" name="image_data" value="">
                    </div>
                    <div class="image-preview" id="informalPreview"></div>
                </div>
                
                <button type="submit" class="submit-btn">Post Item</button>
            </form>
        </div>

    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>

    <script>
        function selectSellerType(type) {
            document.getElementById('sellerSelection').style.display = 'none';
            
            if (type === 'casual') {
                document.getElementById('casualForm').classList.add('active');
            } else if (type === 'informal') {
                document.getElementById('informalForm').classList.add('active');
            }
        }
        
        function goBack() {
            document.getElementById('sellerSelection').style.display = 'block';
            document.getElementById('casualForm').classList.remove('active');
            document.getElementById('informalForm').classList.remove('active');
        }
        
        function previewImages(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files) {
                const files = Array.from(input.files);
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'image-preview-item';
                            div.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
        
        function prepareImages(input, hiddenId) {
            console.log('prepareImages called', input, hiddenId);
            const hiddenInput = document.getElementById(hiddenId);
            console.log('hiddenInput', hiddenInput);
            if (input.files && input.files[0]) {
                console.log('Reading file', input.files[0]);
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('FileReader result', e.target.result.substring(0, 50));
                    hiddenInput.value = e.target.result;
                    console.log('Hidden input value set');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                console.log('No files selected');
            }
        }
    </script>
</body>
</html>
