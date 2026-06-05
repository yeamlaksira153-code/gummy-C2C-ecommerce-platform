<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$order_id = $_GET['order_id'] ?? null;
$listing_id = $_GET['listing_id'] ?? null;

if (!$order_id || !$listing_id) {
    header('Location: index.php');
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT o.*, l.title, l.price FROM orders o JOIN listings l ON o.product_id = l.id WHERE o.id = ? AND o.buyer_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Check if escrow is required
$requires_escrow = $_SESSION['order_requires_escrow_' . $order_id] ?? 1;

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'complete_payment') {
        // Generate fake transaction ID
        $txn_id = 'TXN-' . strtoupper(substr(md5(time() . rand()), 0, 12));
        
        if ($requires_escrow && $order['delivery_method'] === 'courier') {
            // Update order to mark as held in escrow
            $updateStmt = $pdo->prepare(
                "UPDATE orders SET 
                    status = 'HELD_IN_ESCROW', 
                    payment_status = 'escrow_held',
                    paypal_transaction_id = ?,
                    payment_held_at = NOW()
                WHERE id = ?"
            );
            $updateStmt->execute([$txn_id, $order_id]);
            
            // Notify seller about escrow
            $sellerStmt = $pdo->prepare("SELECT email, full_name FROM users WHERE id = ?");
            $sellerStmt->execute([$order['seller_id']]);
            $seller = $sellerStmt->fetch();
            if ($seller) {
                $subject = "Payment Held in Escrow - Order #$order_id";
                $message = "A customer has paid R" . number_format($order['amount'], 2, '.', ',') . " for your item and the money is held in escrow.\n\n";
                $message .= "Item: " . $order['title'] . "\n";
                $message .= "Order ID: $order_id\n";
                $message .= "Transaction ID: $txn_id\n\n";
                $message .= "Please confirm the item's availability so we can proceed with delivery.";
                @mail($seller['email'], $subject, $message);
            }
        } else {
            // Meetup: no escrow, mark as completed/paid
            $updateStmt = $pdo->prepare(
                "UPDATE orders SET 
                    status = 'PENDING_MEETUP', 
                    payment_status = 'paid',
                    paypal_transaction_id = ?,
                    payment_held_at = NOW()
                WHERE id = ?"
            );
            $updateStmt->execute([$txn_id, $order_id]);
        }
        
        // Clear session variable
        unset($_SESSION['order_requires_escrow_' . $order_id]);
        
        // Redirect to track order
        header('Location: trackorder.php?payment_success=1');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:#237790;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .payment-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #237790;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #555;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
        .payment-method {
            margin-bottom: 25px;
        }
        .payment-method h3 {
            font-size: 14px;
            color: #666;
            margin: 0 0 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .card-form {
            display: grid;
            gap: 12px;
            width:100%
        }
        .form-group {
            display: flex;
            gap: 12px;
            flex-wrap:wrap;
        }
        .form-group input { flex: 1;   min-width:120px;}
        input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
             width: 100%; 
    max-width: 100%;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-pay {
            background: #237790;
            color: white;
        }
        .btn-pay:hover { background: #237791; }
        .btn-cancel {
            background: #e9ecef;
            color: #333;
        }
        .btn-cancel:hover { background: #dee2e6; }
        .security-info {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
        .test-card-info {
            background: #e7f3ff;
            border: 1px;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #004085;
        }
        .delivery-info {
            background: #fff3f0;
            border: 1px;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="header">
            <h2>Secure Payment</h2>
            <p>Complete your purchase securely</p>
        </div>

        <div class="order-summary">
            <div class="summary-row">
                <span>Item: <?php echo htmlspecialchars($order['title']); ?></span>
                <span>R <?php echo number_format($order['price'], 2, '.', ','); ?></span>
            </div>
            <div class="summary-row">
                <span><?php echo ucfirst($order['delivery_method']) === 'Courier' ? 'Delivery' : 'Meetup'; ?></span>
                <span><?php echo $order['delivery_method'] === 'courier' ? '+ R 80.00' : 'No fee'; ?></span>
            </div>
            <div class="summary-row">
                <span>Total Amount</span>
                <span>R <?php echo number_format($order['amount'], 2, '.', ','); ?></span>
            </div>
        </div>

        <?php if ($requires_escrow && $order['delivery_method'] === 'courier'): ?>
            <div class="delivery-info">
                <strong> Escrow Protected:</strong> Your payment will be held securely and released after you confirm delivery.
            </div>
        <?php else: ?>
            <div class="delivery-info">
                <strong> Meetup:</strong> Pay the item price now. Arrange meetup with the seller to collect the item.
            </div>
        <?php endif; ?>



        <form method="POST">
            <div class="payment-method">
                <h3>Card Details</h3>
                <div class="card-form">
                    <input type="text" name="cardholder" placeholder="Cardholder Name" required>
                    <input type="text" name="cardnumber" placeholder="Card Number" maxlength="16" required>
                    <div class="form-group">
                        <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                        <input type="text" name="cvc" placeholder="CVC" maxlength="4" required>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" name="action" value="complete_payment" class="btn-pay">Pay R <?php echo number_format($order['amount'], 2, '.', ''); ?></button>
                <a href="buy.php?listing_id=<?php echo htmlspecialchars($listing_id); ?>" style="text-decoration:none;">
                    <button type="button" class="btn-cancel">Cancel</button>
                </a>
            </div>
        </form>

        <div class="security-info">
             Your payment information is secure and encrypted
        </div>
    </div>
</body>
</html>
