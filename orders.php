<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$message = '';
$messageType = '';

// Handle confirm delivery - ESCROW PAYMENT RELEASE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delivery'])) {
    $order_id = $_POST['order_id'];

    // Check if order belongs to user and status is DELIVERED
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND buyer_id = ? AND status = 'DELIVERED'");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch();

    if ($order) {
        // Update to COMPLETED and RELEASE ESCROW PAYMENT to seller
        $updateStmt = $pdo->prepare(
            "UPDATE orders SET 
                status = 'COMPLETED',
                payment_status = 'released_to_seller',
                buyer_confirmed_at = NOW(),
                payment_released_at = NOW()
            WHERE id = ?"
        );
        $updateStmt->execute([$order_id]);

        // Get seller info and send notification
        $sellerStmt = $pdo->prepare("SELECT email, full_name FROM users WHERE id = ?");
        $sellerStmt->execute([$order['seller_id']]);
        $seller = $sellerStmt->fetch();
        
        if ($seller) {
            $subject = "Payment Released - Order #$order_id";
            $paymentMessage = "Customer has confirmed receipt.\n\n";
            $paymentMessage .= "ESCROW PAYMENT RELEASED!\n";
            $paymentMessage .= "Amount: R " . number_format($order['amount'], 2) . " ZAR\n";
            $paymentMessage .= "The payment has been transferred to your account.\n";
            $paymentMessage .= "Order ID: $order_id\n";
            $paymentMessage .= "Transaction ID: " . ($order['paypal_transaction_id'] ?? 'N/A');
            
            @mail($seller['email'], $subject, $paymentMessage);
        }

        // Log the payment release
        error_log("ESCROW PAYMENT RELEASED - Order: $order_id, Amount: {$order['amount']}, Seller ID: {$order['seller_id']}");

        $message = 'Delivery confirmed! The escrow payment (' . number_format($order['amount'], 2) . ' ZAR) has been released to the seller.';
        $messageType = 'success';
    }
}

// Get user's orders with payment status
$stmt = $pdo->prepare("SELECT o.*, l.title as product_name, u.full_name as seller_name
                        FROM orders o
                        JOIN listings l ON o.product_id = l.id
                        JOIN users u ON o.seller_id = u.id
                        WHERE o.buyer_id = ?
                        ORDER BY o.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

// Get escrow statistics
$escrowStats = [
    'total_held' => 0,
    'held_count' => 0,
    'released_count' => 0
];
$statsStmt = $pdo->prepare(
    "SELECT 
        COALESCE(SUM(CASE WHEN payment_status = 'escrow_held' THEN amount ELSE 0 END), 0) as total_held,
        COUNT(CASE WHEN payment_status = 'escrow_held' THEN 1 END) as held_count,
        COUNT(CASE WHEN payment_status = 'released_to_seller' THEN 1 END) as released_count
    FROM orders
    WHERE buyer_id = ?"
);
$statsStmt->execute([$_SESSION['user_id']]);
$escrowStats = $statsStmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - GUMMY Marketplace</title>
  <style>
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }
    /* NAVBAR RESPONSIVENESS */
    .navbar {
        display: flex;
        align-items: center;
        background: #097c87;
        padding: 10px 20px;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
    }
    .logo {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .logo-img {
        max-height: 50px;
        width: auto;
        display: block;
    }
    .nav-links {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-left: auto;
    }
    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 20px;
        transition: background 0.3s;
    }
    .nav-links a:hover {
        background: rgba(255,255,255,0.1);
    }

    .container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 0 15px;
    }

    /* GRID FOR SIDE-BY-SIDE ITEMS */
    .orders-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Forces 2 columns on mobile */
        gap: 15px;
        background: transparent; /* Changed from white to allow gap spacing */
    }

    .order-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        flex-direction: column; /* Stack details vertically inside the card */
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-bottom: none;
    }

    .order-details h3 {
        margin: 0 0 8px 0;
        color: #333;
        font-size: 16px;
        word-break: break-word;
    }
    .order-details p {
        margin: 4px 0;
        color: #666;
        font-size: 13px;
    }

    .order-status {
        display: inline-block;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 11px;
        margin: 10px 0;
    }

    /* Status Colors (Unchanged) */
    .status-pending { background: #e2e3e5; color: #383d41; }
    .status-held { background: #fff3cd; color: #856404; }
    .status-shipped { background: #cce5ff; color: #004085; }
    .status-delivered { background: #d4edda; color: #155724; }
    .status-completed { background: #d1ecf1; color: #0c5460; }

    .order-actions {
        display: flex;
        flex-direction: column; /* Stack buttons to save width on mobile */
        gap: 8px;
        width: 100%;
    }

    .btn {
        padding: 10px 5px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 12px;
        text-align: center;
        width: 100%;
    }
    .btn-track { background: #007bff; color: white; }
    .btn-message { background: #6c757d; color: white; }
    .btn-confirm { background: #28a745; color: white; }

    .alert {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .footer {
        background: #333;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
    }

    /* DESKTOP ADJUSTMENTS (Screens wider than 768px) */
    @media (min-width: 768px) {
        .orders-list {
            grid-template-columns: 1fr; 
        }
        .order-item {
            flex-direction: row; /* Horizontal layout for desktop */
            padding: 20px;
        }
        .order-actions {
            flex-direction: row;
            width: auto;
        }
        .btn {
            width: auto;
            padding: 8px 15px;
        }
        .order-details h3 {
            font-size: 18px;
        }
        .order-status {
            font-size: 13px;
        }
    }

   
    @media (max-width: 360px) {
        .orders-list {
            grid-template-columns: 1fr; /* Switch to 1 column if screen is too tiny */
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
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="casualtraders.php">Casual</a>
            <a href="informaltraders.php">Informal</a>
            <a href="mylistings.php">My Listings</a>
            <a href="messages.php">Messages</a>
            <a href="orders.php">Orders</a>
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <h1>My Orders</h1>

        
            <strong> Escrow Payment Protection:</strong> Your payment is held safely until you confirm receipt. Sellers get paid only after your approval.
            <?php if ($escrowStats['held_count'] > 0): ?>
                <br><small style="color: #666;">You have <strong><?php echo $escrowStats['held_count']; ?></strong> payment(s) held in escrow totaling <strong>R <?php echo number_format($escrowStats['total_held'], 2); ?> ZAR</strong></small>
            <?php endif; ?>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <p style="padding: 20px; text-align: center; color: #666;">No orders yet.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $method = strtolower($order['delivery_method'] ?? '');
                        $isDelivery = ($method === 'courier' || $method === 'delivery' || strpos($method, 'courier') !== false || strpos($method, 'deliver') !== false);
                    ?>
                    <div class="order-item">
                        <div class="order-details">
                            <h3><?php echo htmlspecialchars($order['product_name']); ?></h3>
                            <p>Order #<?php echo $order['id']; ?> | Seller: <?php echo htmlspecialchars($order['seller_name']); ?></p>
                            <p>Amount: R <?php echo number_format($order['amount'], 2); ?> | Delivery Option: <strong><?php echo ucfirst(htmlspecialchars($order['delivery_method'])); ?></strong></p>
                            <?php if ($order['tracking_number']): ?>
                                <p>Tracking: <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="order-status status-<?php echo strtolower(str_replace('_', '-', $order['status'])); ?>">
                                <?php
                                $statusText = [
                                    'PENDING' => 'Pending Payment',
                                    'HELD_IN_ESCROW' => ' In Escrow',
                                    'SHIPPED' => 'In Transit ',
                                    'DELIVERED' => 'Delivered',
                                    'COMPLETED' => 'Completed ',
                                    'CANCELLED' => 'Cancelled',
                                    'REFUNDED' => 'Refunded'
                                ];
                                echo $statusText[$order['status']] ?? $order['status'];
                                ?>
                            </div>
                            <?php if ($order['status'] === 'HELD_IN_ESCROW' && $order['payment_held_at']): ?>
                                <small style="display: block; color: #666; margin-top: 5px;">Payment held since: <?php echo date('M d, Y', strtotime($order['payment_held_at'])); ?></small>
                            <?php elseif ($order['status'] === 'COMPLETED' && $order['payment_released_at']): ?>
                                <small style="display: block; color: #666; margin-top: 5px;">Payment released: <?php echo date('M d, Y', strtotime($order['payment_released_at'])); ?></small>
                            <?php endif; ?>
                            <div class="order-actions" style="margin-top: 10px;">
                                <?php if ($isDelivery): ?>
                                    <a href="trackorder.php" class="btn btn-track">Track Package</a>
                                <?php endif; ?>
                                <a href="messages.php?action=new&listing_id=<?php echo $order['product_id']; ?>&seller_id=<?php echo $order['seller_id']; ?>" class="btn btn-message">Message Seller</a>
                                <?php if ($order['status'] === 'DELIVERED'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" name="confirm_delivery" class="btn btn-confirm">Confirm Received</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>
</body>
</html>
