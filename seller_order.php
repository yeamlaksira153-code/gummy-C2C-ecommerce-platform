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

// Get user's incoming sales orders with payment status
$stmt = $pdo->prepare("SELECT o.*, l.title as product_name, u.full_name as buyer_name
                        FROM orders o
                        JOIN listings l ON o.product_id = l.id
                        JOIN users u ON o.buyer_id = u.id
                        WHERE o.seller_id = ?
                        ORDER BY o.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

// Get escrow statistics for the seller's incoming earnings
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
    WHERE seller_id = ?"
);
$statsStmt->execute([$_SESSION['user_id']]);
$escrowStats = $statsStmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Sales & Earnings - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
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
        .logo { display: flex; align-items: center; flex-shrink: 0; }
        .logo-img { max-height: 60px; width: auto; display: block; }
        .nav-links { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-left: auto; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; padding: 8px 15px; border-radius: 20px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.1); }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .orders-list {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-item:last-child { border-bottom: none; }
        .order-details h3 { margin: 0 0 10px 0; color: #333; }
        .order-details p { margin: 5px 0; color: #666; }
        .order-status { font-weight: bold; padding: 5px 10px; border-radius: 5px; }
        .status-pending { background: #e2e3e5; color: #383d41; }
        .status-held { background: #fff3cd; color: #856404; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .order-actions { display: flex; gap: 10px; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-track { background: #007bff; color: white; }
        .btn-message { background: #6c757d; color: white; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .footer { background: #333; color: white; text-align: center; padding: 20px; margin-top: 40px; }
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
            <a href="seller_order.php">Sales & Earnings</a>
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <h1>My Sales & Earnings</h1>

        <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong> Incoming Escrow Balance:</strong> Buyer payments are held safely in escrow. Funds are automatically transferred into your account as soon as the buyer confirms delivery.
            <?php if ($escrowStats['held_count'] > 0): ?>
                <br><small style="color: #666;">You have <strong><?php echo $escrowStats['held_count']; ?></strong> pending payment(s) held in escrow totaling <strong>R <?php echo number_format($escrowStats['total_held'], 2); ?> ZAR</strong></small>
            <?php endif; ?>
        </div>

        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <p style="padding: 20px; text-align: center; color: #666;">No sales transactions yet.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $method = strtolower($order['delivery_method'] ?? '');
                        $isDelivery = ($method === 'courier' || $method === 'delivery' || strpos($method, 'courier') !== false || strpos($method, 'deliver') !== false);
                    ?>
                    <div class="order-item">
                        <div class="order-details">
                            <h3><?php echo htmlspecialchars($order['product_name']); ?></h3>
                            <p>Order #<?php echo $order['id']; ?> | Buyer: <?php echo htmlspecialchars($order['buyer_name']); ?></p>
                            <p>Amount: R <?php echo number_format($order['amount'], 2); ?> | Delivery Option: <strong><?php echo ucfirst(htmlspecialchars($order['delivery_method'])); ?></strong></p>
                            <?php if ($order['tracking_number']): ?>
                                <p>Tracking: <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="order-status status-<?php echo strtolower(str_replace('_', '-', $order['status'])); ?>">
                                <?php
                                $statusText = [
                                    'PENDING' => 'Awaiting Payment',
                                    'HELD_IN_ESCROW' => 'Payment in Escrow',
                                    'SHIPPED' => 'In Transit',
                                    'DELIVERED' => 'Delivered',
                                    'COMPLETED' => 'Completed & Released',
                                    'CANCELLED' => 'Cancelled',
                                    'REFUNDED' => 'Refunded'
                                ];
                                echo $statusText[$order['status']] ?? $order['status'];
                                ?>
                            </div>
                            <?php if ($order['status'] === 'HELD_IN_ESCROW' && $order['payment_held_at']): ?>
                                <small style="display: block; color: #666; margin-top: 5px;">Held in escrow since: <?php echo date('M d, Y', strtotime($order['payment_held_at'])); ?></small>
                            <?php elseif ($order['status'] === 'COMPLETED' && $order['payment_released_at']): ?>
                                <small style="display: block; color: #28a745; font-weight: bold; margin-top: 5px;">Payment released into your account: <?php echo date('M d, Y', strtotime($order['payment_released_at'])); ?></small>
                            <?php endif; ?>
                            <div class="order-actions" style="margin-top: 10px;">
                                <?php if ($isDelivery): ?>
                                    <a href="trackorder.php" class="btn btn-track">Track Package</a>
                                <?php endif; ?>
                                <a href="messages.php?action=new&listing_id=<?php echo $order['product_id']; ?>&seller_id=<?php echo $order['buyer_id']; ?>" class="btn btn-message">Message Buyer</a>
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
