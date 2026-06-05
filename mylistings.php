<?php
session_start();

// Handle delete listing
if (isset($_POST['delete_listing'])) {
    require_once 'db.php';
    $listingId = $_POST['listing_id'] ?? 0;
    $userId = $_SESSION['user_id'] ?? 0;
    
    if ($listingId > 0 && $userId > 0) {
        try {
            // Delete listing 
            $stmt = $pdo->prepare("DELETE FROM listings WHERE id = ? AND user_id = ?");
            $stmt->execute([$listingId, $userId]);
            $message = 'Listing deleted successfully!';
        } catch (PDOException $e) {
            $message = 'Error deleting listing: ' . $e->getMessage();
        }
    }
}

// Handle update listing
if (isset($_POST['update_listing'])) {
    require_once 'db.php';
    $listingId = $_POST['listing_id'] ?? 0;
    $userId = $_SESSION['user_id'] ?? 0;
    
    if ($listingId > 0 && $userId > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE listings SET title = ?, description = ?, price = ?, location = ?, status = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([
                $_POST['title'] ?? '',
                $_POST['description'] ?? '',
                $_POST['price'] ?? 0,
                $_POST['location'] ?? '',
                $_POST['status'] ?? 'active',
                $listingId,
                $userId
            ]);
            $message = 'Listing updated successfully!';
        } catch (PDOException $e) {
            $message = 'Error updating listing: ' . $e->getMessage();
        }
    }
}

$message = $message ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings - GUMMY Marketplace</title>
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
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links a {
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
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .nav-links a:hover {
            color: #ddd;
        }
        .menu-toggle {
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* MAIN CONTENT */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        h1 {
            color: #097c87;
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* TABS */
        .tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            color: #097c87;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: 2px solid #097c87;
        }
        .tab-btn:hover {
            background: #f0f9fa;
        }
        .tab-btn.active {
            background: #097c87;
            color: white;
        }

        /* LISTINGS GRID - Vinted-style */
        .listings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
            padding: 10px;
        }
        .listing-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .listing-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f5f5f5;
        }
        .listing-content {
            padding: 12px;
        }
        .listing-title {
            margin: 0 0 6px 0;
            color: #222;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 36px;
        }
        .listing-price {
            font-size: 16px;
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }
        .listing-meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }
        .listing-description {
            color: #666;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .listing-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            flex: 1;
        }
        .btn-edit {
            background-color: #222;
            color: white;
        }
        .btn-edit:hover {
            background-color: #000;
        }
        .btn-delete {
            background-color: #ff4444;
            color: white;
        }
        .btn-delete:hover {
            background-color: #cc0000;
        }
        
        /* Edit Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            margin: 8% auto;
            padding: 24px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
        }
        .modal h2 {
            margin: 0 0 20px 0;
            font-size: 18px;
            color: #222;
        }
        .modal .form-group {
            margin-bottom: 12px;
        }
        .modal label {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #666;
        }
        .modal input, .modal textarea, .modal select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .modal-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 16px;
        }
        .modal-buttons button {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .modal-buttons .btn-save {
            background-color: #222;
            color: white;
        }
        .modal-buttons .btn-cancel {
            background-color: #eee;
            color: #333;
        }
        
        .listing-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: #d4edda;
            color: #155724;
        }
        .listing-seller-type {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 10px;
            background: #f0f9fa;
            color: #097c87;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #666;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .empty-state .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .empty-state p {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .empty-state a {
            color: #097c87;
            font-weight: 600;
            text-decoration: none;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            background: #097c87;
            color: white;
            margin-top: 40px;
        }

        /* RESPONSIVE */
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
            
            .listings-grid {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                gap: 8px;
            }
            .tab-btn {
                padding: 10px 18px;
                font-size: 14px;
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

    <div class="container">
        <h1>My Listings</h1>
        
        <?php if (!empty($message)): ?>
        <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="tabs">
            <button class="tab-btn active" onclick="filterListings('all')">All Listings</button>
            <button class="tab-btn" onclick="filterListings('casual')">Casual</button>
            <button class="tab-btn" onclick="filterListings('informal')">Informal</button>
<a href="seller_order.php">
    <button class="tab-btn">sells&Ernings</button>
</a>

         
        </div>
        
        <div class="listings-grid" id="listingsGrid">
            <?php
            // Include database connection
            require_once 'db.php';
            
            $currentUserId = $_SESSION['user_id'] ?? 0;
            
            // Get user's listings from database
            $stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$currentUserId]);
            $listings = $stmt->fetchAll();
            
            // Get images for each listing
            foreach ($listings as &$listing) {
                $imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
                $imgStmt->execute([$listing['id']]);
                $listing['images'] = $imgStmt->fetchAll();
            }
            
            if (empty($listings)):
            ?>
            <div class="empty-state" style="grid-column: 1 / -1;">
                <p>No listings yet</p>
                <p>Start selling by creating your first listing!</p>
                <a href="sell.php">+ Create Listing</a>
            </div>
            <?php else: ?>
                <?php foreach ($listings as $listing): ?>
                <div class="listing-card <?php echo $listing['seller_type']; ?>" data-type="<?php echo $listing['seller_type']; ?>">
                    <?php if (!empty($listing['images']) && isset($listing['images'][0]['image_path'])): ?>
                        <img src="../<?php echo $listing['images'][0]['image_path']; ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>" class="listing-image">
                    <?php else: ?>
                        <div class="listing-image" style="display: flex; align-items: center; justify-content: center; font-size: 50px; color: #999;">&#128247;</div>
                    <?php endif; ?>
                    <div class="listing-content">
                        <h3 class="listing-title">
                            <?php echo htmlspecialchars($listing['title']); ?>
                            <span class="listing-seller-type"><?php echo $listing['seller_type']; ?></span>
                        </h3>
                        <div class="listing-price">R <?php echo number_format($listing['price'], 0, ',', ' '); ?></div>
                        <div class="listing-meta">
                            <span><?php echo htmlspecialchars($listing['location']); ?></span>
                            <span><?php echo date('M d, Y', strtotime($listing['created_at'])); ?></span>
                        </div>
                        <p class="listing-description"><?php echo htmlspecialchars($listing['description']); ?></p>
                        <span class="listing-status"><?php echo ucfirst($listing['status']); ?></span>
                        <div class="listing-actions">
                            <button onclick="editListing(<?php echo $listing['id']; ?>, '<?php echo htmlspecialchars($listing['title']); ?>', '<?php echo htmlspecialchars($listing['description']); ?>', '<?php echo $listing['price']; ?>', '<?php echo htmlspecialchars($listing['location']); ?>', '<?php echo $listing['status']; ?>')" class="btn-edit">Edit</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                <button type="submit" name="delete_listing" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Listing Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit Listing</h2>
            <form method="POST">
                <input type="hidden" name="listing_id" id="edit_listing_id">
                <input type="hidden" name="update_listing" value="1">
                
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="edit_description" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Price (R)</label>
                    <input type="number" name="price" id="edit_price" required>
                </div>
                
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="edit_location" required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="edit_status">
                        <option value="active">Active</option>
                        <option value="sold">Sold</option>
                        <option value="removed">Removed</option>
                    </select>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function editListing(id, title, description, price, location, status) {
            document.getElementById('edit_listing_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_status').value = status;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeModal();
            }
        }
    </script>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>

    <script>
        function filterListings(type) {
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter cards
            const cards = document.querySelectorAll('.listing-card');
            cards.forEach(card => {
                if (type === 'all' || card.dataset.type === type) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
