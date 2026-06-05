<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    $redirect = isset($_GET['listing_id']) ? ('buy.php?listing_id=' . urlencode($_GET['listing_id'])) : 'buy.php';
    header('Location: auth/login.php?redirect=' . $redirect);
    exit;
}

$listing_id = $_GET['listing_id'] ?? null;
if (!$listing_id) {
    header('Location: index.php');
    exit;
}

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

if ($listing['user_id'] == $_SESSION['user_id']) {
    header('Location: mylistings.php');
    exit;
}

$imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
$imgStmt->execute([$listing['id']]);
$images = $imgStmt->fetchAll();

$itemPrice = (float) $listing['price'];
$courierFee = 80.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Item - GUMMY Marketplace</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar {
            display: flex;
            align-items: center;
            background: #097c87;
            padding: 10px 20px;
            gap: 15px;
            flex-wrap: wrap;
        }
        .logo { display: flex; align-items: center; }
        .logo-img { max-height: 60px; width: auto; display: block; }
        .nav-links { display: flex; align-items: center; gap: 15px; margin-left: auto; flex-wrap: wrap; }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }

        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .purchase-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .listing-preview {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .listing-preview img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-fallback {
            width: 200px;
            height: 150px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #666;
        }

        .listing-info h2 { margin: 0 0 10px 0; color: #333; }
        .listing-info .price { font-size: 24px; color: #097c87; font-weight: bold; margin-bottom: 10px; }
        .listing-info .seller { color: #666; margin-bottom: 5px; }
        .listing-info .location { color: #999; }

        .verified-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #d4edda;
            color: #155724;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
        }
        .unverified-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #f8d7da;
            color: #721c24;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
        }

        .purchase-options { padding: 30px; }
        .purchase-options h3 { margin: 0 0 12px 0; color: #333; }

        .estimator {
            margin-top: 12px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            color: #333;
            font-size: 14px;
        }

        .warning {
            margin-top: 12px;
            background: #fff3f3;
            border: 1px solid #f5c2c2;
            color: #721c24;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
        }

        .button-row { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
        .btn {
            display: inline-block;
            border: none;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
        }
        .btn-buy { background: #097c87; color: white; min-width: 170px; }
        .btn-buy:hover { background: #065a63; }
        .btn-msg { background: #6c757d; color: white; min-width: 170px; }
        .btn-msg:hover { background: #5a6268; }

        .modal-backdrop {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1000;
        }
        .modal {
            background: #fff;
            border-radius: 10px;
            max-width: 580px;
            width: 100%;
            padding: 22px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .modal h3 { margin: 0 0 12px 0; }

        .line { display: flex; justify-content: space-between; margin-bottom: 8px; color: #333; }
        .line.total { margin-top: 10px; font-weight: bold; font-size: 18px; }

        .choice {
            border: 1px solid #d7d7d7;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
        }
        .choice.active {
            border-color: #097c87;
            background: #f0f9fa;
        }
        .choice label {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            cursor: pointer;
        }
        .choice-title { font-weight: bold; color: #333; }
        .choice-note { font-size: 13px; color: #666; margin-top: 2px; }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }
        .btn-primary { background: #097c87; color: white; flex: 1; }
        .btn-primary:hover { background: #065a63; }
        .btn-cancel { background: #e9ecef; color: #333; width: 140px; }
        .btn-cancel:hover { background: #dde2e6; }

        .footer { background: #333; color: white; text-align: center; padding: 20px; margin-top: 40px; }

        @media (max-width: 900px) {
            .listing-preview { flex-direction: column; }
            .listing-preview img, .image-fallback { width: 100%; height: 220px; }
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
        <div class="purchase-card">
            <div class="listing-preview">
                <?php if (!empty($images)): ?>
                    <img src="../<?php echo htmlspecialchars($images[0]['image_path']); ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>">
                <?php else: ?>
                    <div class="image-fallback">No Image</div>
                <?php endif; ?>

                <div class="listing-info">
                    <h2><?php echo htmlspecialchars($listing['title']); ?></h2>
                    <div class="price">R <?php echo number_format($itemPrice, 2, '.', ','); ?></div>
                    <div class="seller">
                        Seller: <?php echo htmlspecialchars($listing['seller_name']); ?>
                        <?php if (!empty($listing['id_verified'])): ?>
                            <span class="verified-badge">Verified</span>
                        <?php else: ?>
                            <span class="unverified-badge">Unverified</span>
                        <?php endif; ?>
                    </div>
                    <div class="location"><?php echo htmlspecialchars($listing['location']); ?></div>
                </div>
            </div>

            <div class="purchase-options">
                <h3>Purchase</h3>
                <div class="estimator">
                    Courier delivery fee: R <?php echo number_format($courierFee, 2, '.', ','); ?>. Meetup option has no delivery charge.
                </div>

                <?php if (empty($listing['id_verified'])): ?>
                    <div class="warning">
                        <strong>Warning:</strong> This seller is unverified. Proceed carefully.
                    </div>
                <?php endif; ?>

                <div class="button-row">
                    <button id="buy-now" class="btn btn-buy" type="button">Buy Now</button>
                    <a href="messages.php?action=new&listing_id=<?php echo urlencode((string) $listing_id); ?>&seller_id=<?php echo urlencode((string) $listing['seller_id']); ?>" class="btn btn-msg">Contact Seller</a>
                </div>
            </div>
        </div>
    </div>

    <div id="payment-modal" class="modal-backdrop">
        <div class="modal">
            <h3>Payment Summary</h3>

            <div class="choice active" id="choice-courier-box">
                <label>
                    <input type="radio" name="delivery_choice" value="courier" checked>
                    <div>
                        <div class="choice-title">Courier Delivery (+ R <?php echo number_format($courierFee, 2, '.', ','); ?>)</div>
                        <div class="choice-note">Escrow protected and trackable.</div>
                    </div>
                </label>
            </div>

            <div class="choice" id="choice-meetup-box">
                <label>
                    <input type="radio" name="delivery_choice" value="meetup">
                    <div>
                        <div class="choice-title">Arrange Meetup (+ R 0.00)</div>
                        <div class="choice-note">No delivery charge.</div>
                    </div>
                </label>
            </div>

            <hr>

            <div class="line"><span>Item</span><span id="sum-item">R 0.00</span></div>
            <div class="line"><span>Delivery</span><span id="sum-delivery">R 0.00</span></div>
            <div class="line total"><span>Total</span><span id="sum-total">R 0.00</span></div>

            <form id="payment-form" action="checkout_new.php?listing_id=<?php echo urlencode((string) $listing_id); ?>" method="POST">
                <input type="hidden" name="delivery_method" id="form-delivery-method" value="courier">
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                    <button id="cancel-modal" type="button" class="btn btn-cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>

    <script>
        (function () {
            const itemPrice = <?php echo json_encode($itemPrice); ?>;
            const courierFee = <?php echo json_encode($courierFee); ?>;

            const buyBtn = document.getElementById('buy-now');
            const modal = document.getElementById('payment-modal');
            const cancelBtn = document.getElementById('cancel-modal');
            const sumItem = document.getElementById('sum-item');
            const sumDelivery = document.getElementById('sum-delivery');
            const sumTotal = document.getElementById('sum-total');
            const hiddenDelivery = document.getElementById('form-delivery-method');

            const courierBox = document.getElementById('choice-courier-box');
            const meetupBox = document.getElementById('choice-meetup-box');
            const radios = document.querySelectorAll('input[name="delivery_choice"]');

            function currency(amount) {
                return 'R ' + Number(amount).toFixed(2);
            }

            function selectedMethod() {
                const selected = document.querySelector('input[name="delivery_choice"]:checked');
                return selected ? selected.value : 'courier';
            }

            function updateSummary() {
                const method = selectedMethod();
                const delivery = method === 'courier' ? courierFee : 0;
                const total = itemPrice + delivery;

                hiddenDelivery.value = method;
                sumItem.textContent = currency(itemPrice);
                sumDelivery.textContent = currency(delivery);
                sumTotal.textContent = currency(total);

                if (method === 'courier') {
                    courierBox.classList.add('active');
                    meetupBox.classList.remove('active');
                } else {
                    courierBox.classList.remove('active');
                    meetupBox.classList.add('active');
                }
            }

            buyBtn.addEventListener('click', function () {
                updateSummary();
                modal.style.display = 'flex';
            });

            cancelBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            radios.forEach(function (radio) {
                radio.addEventListener('change', updateSummary);
            });
        })();
    </script>
</body>
</html>
