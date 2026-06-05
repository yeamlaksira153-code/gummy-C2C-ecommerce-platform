<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php?redirect=checkout_new.php?listing_id=' . ($_GET['listing_id'] ?? ''));
    exit;
}

// Get listing ID
$listing_id = $_GET['listing_id'] ?? null;

if (!$listing_id) {
    header('Location: index.php');
    exit;
}

// Get listing details
$stmt = $pdo->prepare("SELECT l.*, u.id as seller_id, u.full_name as seller_name, u.phone as seller_phone, u.id_verified
                        FROM listings l
                        JOIN users u ON l.user_id = u.id
                        WHERE l.id = ?");
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

if (!$listing) {
    header('Location: index.php');
    exit;
}

// Check if user is trying to buy their own listing
if ($listing['user_id'] == $_SESSION['user_id']) {
    header('Location: mylistings.php');
    exit;
}

$message = '';
$messageType = '';

// Handle delivery selection and proceed to payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivery_method'])) {
    $delivery_method = $_POST['delivery_method'];

    if ($delivery_method === 'chat') {
        // Redirect to chat
        header('Location: messages.php?action=new&listing_id=' . $listing_id . '&seller_id=' . $listing['seller_id']);
        exit;
    }

    $amount = $listing['price'];
    $requires_escrow = 1; // Default: escrow required for delivery

    if ($delivery_method === 'courier') {
        $amount += 80; // Add courier delivery fee
    } elseif ($delivery_method === 'meetup') {
        // Meetup: no delivery fee, no escrow needed
        $requires_escrow = 0;
    }

    // Create order in database with status PENDING (default)
    $orderStmt = $pdo->prepare("INSERT INTO orders (buyer_id, seller_id, product_id, amount, delivery_method, status) VALUES (?, ?, ?, ?, ?, 'PENDING')");
    $orderStmt->execute([$_SESSION['user_id'], $listing['seller_id'], $listing_id, $amount, $delivery_method]);
    $order_id = $pdo->lastInsertId();

    // Store requires_escrow in session for payment page
    $_SESSION['order_requires_escrow_' . $order_id] = $requires_escrow;

    // Redirect to fake payment gateway
    header('Location: fake_payment_new.php?order_id=' . $order_id . '&listing_id=' . $listing_id);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .navbar {
            display: flex; align-items: center; background: #097c87; padding: 10px 20px;
            flex-wrap: wrap; position: relative; gap: 15px; width: 100%;
        }
        .logo { display: flex; align-items: center; flex-shrink: 0; }
        .logo-img { max-height: 60px; width: auto; }
        .nav-links { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-left: auto; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; padding: 8px 15px; border-radius: 20px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .checkout-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .order-summary { display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #eee; }
        .order-summary img { width: 200px; height: 150px; object-fit: cover; border-radius: 8px; }
        .order-info h2 { margin: 0 0 10px 0; color: #333; }
        .order-info .price { font-size: 24px; color: #097c87; font-weight: bold; margin-bottom: 10px; }
        .order-info .seller { color: #666; margin-bottom: 5px; }
        .order-info .location { color: #999; }
        .verified-badge { display: inline-block; padding: 3px 8px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px; margin-left: 10px; }
        .unverified-badge { display: inline-block; padding: 3px 8px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px; margin-left: 10px; }
        .delivery-options { padding: 30px; }
        .delivery-options h3 { margin: 0 0 20px 0; color: #333; }
        .option { border: 2px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s; }
        .option:hover { border-color: #097c87; }
        .option input[type="radio"] { display: none; }
        .option input[type="radio"]:checked + .option-content { border-color: #097c87; background: #f0f9fa; }
        .option-content { display: flex; align-items: flex-start; gap: 15px; }
        .option-icon { font-size: 30px; }
        .option-details h4 { margin: 0 0 5px 0; color: #333; }
        .option-details p { margin: 0; color: #666; font-size: 14px; }
        .btn-pay { display: block; width: 100%; padding: 15px; background: #097c87; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: background 0.3s; }
        .btn-pay:hover { background: #065a63; }
        .info-note { background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #004085; font-size: 14px; }
        .footer { background: #333; color: white; text-align: center; padding: 20px; margin-top: 40px; }
        @media (max-width: 900px) {
            .order-summary { flex-direction: column; }
            .order-summary img { width: 100%; }
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
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="casualtraders.php">Casual</a>
            <a href="informaltraders.php">Informal</a>
            <a href="mylistings.php">My Listings</a>
            <a href="messages.php">Messages</a>
            <a href="trackorder.php">Track Order</a>
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <div class="checkout-card">
            <div class="order-summary">
                <?php
                $imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
                $imgStmt->execute([$listing['id']]);
                $images = $imgStmt->fetchAll();
                ?>
                <?php if (!empty($images)): ?>
                    <img src="../<?php echo htmlspecialchars($images[0]['image_path']); ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>">
                <?php else: ?>
                    <div style="width: 200px; height: 150px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px;">No Image</div>
                <?php endif; ?>
                <div class="order-info">
                    <h2><?php echo htmlspecialchars($listing['title']); ?></h2>
                    <div class="price">R <?php echo number_format($listing['price'], 2, '.', ','); ?></div>
                    <div class="seller">
                        Seller: <?php echo htmlspecialchars($listing['seller_name']); ?>
                        <?php if ($listing['id_verified']): ?>
                            <span class="verified-badge">Verified </span>
                        <?php else: ?>
                            <span class="unverified-badge">Unverified</span>
                        <?php endif; ?>
                    </div>
                    <div class="location"><?php echo htmlspecialchars($listing['location']); ?></div>
                </div>
            </div>

            <div class="delivery-options">
                <h3>Select Delivery Option</h3>

                <?php if ($message): ?>
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #155724;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <label class="option">
                        <input type="radio" name="delivery_method" value="courier" checked>
                        <div class="option-content">
                            <div class="option-details">
                                <h4>Courier Delivery</h4>
                                <p>Safe delivery to your door. Money held in escrow until you confirm receipt.</p>
                                <p style="color: #097c87; font-weight: bold; margin-top: 8px;">+ R 80.00</p>
                            </div>
                        </div>
                    </label>

                    <label class="option">
                        <input type="radio" name="delivery_method" value="meetup">
                        <div class="option-content">
                            <div class="option-details">
                                <h4>Arrange Meetup</h4>
                                <p>Meet the seller in person. Pay item price only, no delivery fee.</p>
                                <p style="color: #097c87; font-weight: bold; margin-top: 8px;">No additional cost</p>
                            </div>
                        </div>
                    </label>

                    <label class="option">
                        <input type="radio" name="delivery_method" value="chat">
                        <div class="option-content">
                            <div class="option-details">
                                <h4>Chat & Negotiate</h4>
                                <p>Discuss details with the seller before committing to a purchase.</p>
                            </div>
                        </div>
                    </label>

                    <button type="submit" class="btn-pay">Proceed to Payment</button>
                </form>

                <div class="info-note" style="margin-top: 20px;">
                    <strong>Note:</strong> For courier delivery, your payment is held securely until you confirm receiving the item. For meetup, you pay at the point of sale.
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>
</body>
</html>
