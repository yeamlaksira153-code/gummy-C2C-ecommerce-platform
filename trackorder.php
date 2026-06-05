<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php?redirect=trackorder.php');
    exit;
}

$message = '';
$messageType = '';
$deliverySimulations = [];
$deliveryJsonPath = __DIR__ . '/../data/delivery.json';

if (is_file($deliveryJsonPath)) {
    $deliveryRaw = file_get_contents($deliveryJsonPath);
    $decoded = json_decode($deliveryRaw, true);
    if (is_array($decoded)) {
        $deliverySimulations = $decoded;
    }
}

function normalise_status_label($status)
{
    return ucwords(str_replace(['_', '-'], ' ', (string) $status));
}

function get_simulation_for_order(array $order, array $deliverySimulations)
{
    // FIXED CHECK: Supports all variations of Courier Delivery options
    $method = strtolower($order['delivery_method'] ?? '');
    $isDelivery = ($method === 'courier' || $method === 'delivery' || strpos($method, 'courier') !== false || strpos($method, 'deliver') !== false);
    
    if (!$isDelivery) {
        return null;
    }

    $trackingNumber = $order['tracking_number'] ?? '';
    if ($trackingNumber !== '' && !empty($deliverySimulations)) {
        foreach ($deliverySimulations as $simulation) {
            if (!empty($simulation['tracking_number']) && $simulation['tracking_number'] === $trackingNumber) {
                return $simulation;
            }
        }
    }

    $numericOrderId = (int) preg_replace('/\D+/', '', (string) $order['id']);
    if ($numericOrderId <= 0) {
        $numericOrderId = 1;
    }

    if (!empty($deliverySimulations)) {
        $index = ($numericOrderId - 1) % count($deliverySimulations);
        return $deliverySimulations[$index];
    }

    // Dynamic fallback simulation when the local JSON file is missing
    return [
        'status' => 'in_transit',
        'tracking_number' => 'GUMMY-' . str_pad((string) $numericOrderId, 6, '0', STR_PAD_LEFT),
        'courier' => 'GUMMY Express Logistics',
        'current_location' => 'In Transit to Hub (Kensington, JHB)',
        'estimated_delivery' => '2-3 Business Days'
    ];
}

// Handle "Item Received" confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_received'])) {
    $order_id = intval($_POST['order_id']);
    
    // Verify order belongs to buyer
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND buyer_id = ?");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch();
    
    if ($order) {
        // Mark order as delivered
        $updateStmt = $pdo->prepare("UPDATE orders SET status = 'DELIVERED', buyer_confirmed_at = NOW() WHERE id = ?");
        $updateStmt->execute([$order_id]);
        
        // Mark payment as ready to release
        $method = strtolower($order['delivery_method'] ?? '');
        $isDelivery = ($method === 'courier' || $method === 'delivery' || strpos($method, 'courier') !== false || strpos($method, 'deliver') !== false);
        
        if ($isDelivery) {
            $releaseStmt = $pdo->prepare("UPDATE orders SET payment_status = 'released_to_seller', payment_released_at = NOW() WHERE id = ?");
            $releaseStmt->execute([$order_id]);
            
            // Notify seller that payment is being released
            $sellerStmt = $pdo->prepare("SELECT email, full_name FROM users WHERE id = ?");
            $sellerStmt->execute([$order['seller_id']]);
            $seller = $sellerStmt->fetch();
            if ($seller) {
                $subject = "Payment Released - Order #$order_id";
                $message_text = "The buyer has confirmed receipt of the item. Your payment of R" . number_format($order['amount'], 2, '.', ',') . " is now being released to your account.\n\n";
                $message_text .= "Order ID: $order_id\n";
                $message_text .= "Thank you for selling on GUMMY!";
                @mail($seller['email'], $subject, $message_text);
            }
        }
        
        $message = 'Item marked as received. Thank you!';
        $messageType = 'success';
    }
}

// Seed courier orders with a simulated tracking number from delivery.json
$courierSeedStmt = $pdo->prepare("
    SELECT id, tracking_number 
    FROM orders 
    WHERE buyer_id = ? 
    AND (LOWER(delivery_method) LIKE '%courier%' OR LOWER(delivery_method) LIKE '%deliver%') 
    AND (tracking_number IS NULL OR tracking_number = '') 
    ORDER BY created_at DESC
");
$courierSeedStmt->execute([$_SESSION['user_id']]);
$courierOrdersNeedingTracking = $courierSeedStmt->fetchAll();

if (!empty($courierOrdersNeedingTracking)) {
    $assignStmt = $pdo->prepare("UPDATE orders SET tracking_number = ?, status = ? WHERE id = ? AND buyer_id = ?");
    foreach ($courierOrdersNeedingTracking as $seedOrder) {
        $seedNumericId = (int) preg_replace('/\D+/', '', (string) $seedOrder['id']);
        if ($seedNumericId <= 0) {
            $seedNumericId = 1;
        }

        if (!empty($deliverySimulations)) {
            $simulationIndex = ($seedNumericId - 1) % count($deliverySimulations);
            $simulation = $deliverySimulations[$simulationIndex];
            $trackingNumber = $simulation['tracking_number'] ?? ('GUMMY-' . str_pad((string) $seedNumericId, 6, '0', STR_PAD_LEFT));
            $simulationStatus = $simulation['status'] ?? 'in_transit';
        } else {
            $trackingNumber = 'GUMMY-' . str_pad((string) $seedNumericId, 6, '0', STR_PAD_LEFT);
            $simulationStatus = 'in_transit';
        }

        $statusMap = [
            'pending_pickup' => 'SHIPPED',
            'arrived_at_hub' => 'SHIPPED',
            'in_transit' => 'SHIPPED',
            'out_for_delivery' => 'SHIPPED',
            'delivered' => 'DELIVERED'
        ];
        $orderStatus = $statusMap[$simulationStatus] ?? 'SHIPPED';

        $assignStmt->execute([$trackingNumber, $orderStatus, $seedOrder['id'], $_SESSION['user_id']]);
    }
}

// Get all buyer's active orders (not yet received)
$stmt = $pdo->prepare("
    SELECT o.*, l.title, l.price, u.full_name as seller_name
    FROM orders o
    JOIN listings l ON o.product_id = l.id
    JOIN users u ON o.seller_id = u.id
    WHERE o.buyer_id = ? AND o.status != 'DELIVERED' AND o.status != 'COMPLETED' AND o.status != 'CANCELLED' AND o.status != 'REFUNDED'
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - GUMMY Marketplace</title>
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
            gap: 15px;
            width: 100%;
        }
        .logo { display: flex; align-items: center; flex-shrink: 0; }
        .logo-img { max-height: 60px; width: auto; }
        .nav-links { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-left: auto; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; padding: 8px 15px; border-radius: 20px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .page-title { margin: 0 0 30px 0; color: #333; }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #097c87;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .order-title { font-size: 18px; font-weight: bold; color: #333; margin: 0; }
        .order-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-escrow { background: #fff3cd; color: #856404; }
        .status-meetup { background: #cfe2ff; color: #084298; }
        .order-details {
            display: grid;
            gap: 8px;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .detail-row { display: flex; justify-content: space-between; }
        .detail-label { font-weight: bold; }
        .detail-value { color: #333; }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-confirm {
            background: #097c87;
            color: white;
            flex: 1;
        }
        .btn-confirm:hover { background: #065a63; }
        .btn-contact {
            background: #6c757d;
            color: white;
            flex: 1;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-contact:hover { background: #5a6268; }
        .tracking-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .tracking-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .tracking-status {
            color: #097c87;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .tracking-number {
            background: white;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-family: monospace;
            color: #333;
        }
        .no-orders {
            background: white;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            color: #666;
        }
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
            <a href=" seller_order.php">Sells&Ernings</a>
            <a href="messages.php">Messages</a>
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="page-title">My Orders</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <h2>No Active Orders</h2>
                <p>You haven't made any purchases yet, or all your orders have been completed.</p>
                <a href="alllistings.php" style="color: #097c87; text-decoration: none; font-weight: bold;">Browse Products →</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <?php 
                    // FIXED CHECK: Captures and matches Courier Delivery variations
                    $method = strtolower($order['delivery_method'] ?? '');
                    $isDelivery = ($method === 'courier' || $method === 'delivery' || strpos($method, 'courier') !== false || strpos($method, 'deliver') !== false);
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3 class="order-title"><?php echo htmlspecialchars($order['title']); ?></h3>
                        <span class="order-status <?php echo $isDelivery ? 'status-escrow' : 'status-meetup'; ?>">
                            <?php echo $isDelivery ? 'Escrow Protected' : 'Meetup'; ?>
                        </span>
                    </div>

                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Order ID:</span>
                            <span class="detail-value">#<?php echo htmlspecialchars($order['id']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Seller:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['seller_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Amount:</span>
                            <span class="detail-value">R <?php echo number_format($order['amount'], 2, '.', ','); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value"><?php echo htmlspecialchars(ucfirst(strtolower($order['status']))); ?></span>
                        </div>
                    </div>

                    <?php $simulation = get_simulation_for_order($order, $deliverySimulations); ?>

                    <?php if ($isDelivery && ($simulation || !empty($order['tracking_number']))): ?>
                        <div class="tracking-section">
                            <div class="tracking-title"> Tracking Information</div>
                            <div class="tracking-status">
                                <?php echo htmlspecialchars(normalise_status_label($simulation['status'] ?? ($order['status'] ?? 'in_transit'))); ?>
                            </div>
                            <div class="tracking-number">Tracking #: <?php echo htmlspecialchars($simulation['tracking_number'] ?? $order['tracking_number']); ?></div>
                            <?php if (!empty($simulation['courier'])): ?>
                                <div class="detail-row" style="margin-top: 8px;">
                                    <span class="detail-label">Courier:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($simulation['courier']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($simulation['current_location'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label">Current Location:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($simulation['current_location']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($simulation['estimated_delivery'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label">Estimated Delivery:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($simulation['estimated_delivery']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="button-group">
                        <form method="POST" style="flex: 1; margin: 0;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                            <button type="submit" name="confirm_received" value="1" class="btn btn-confirm">✓ Item Received</button>
                        </form>
                        <a href="messages.php?action=new&user_id=<?php echo htmlspecialchars($order['seller_id']); ?>" class="btn btn-contact"> Contact Seller</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>
</body>
</html>
