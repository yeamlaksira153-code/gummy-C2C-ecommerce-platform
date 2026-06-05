<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse All - GUMMY Marketplace</title>
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

    /* SELLER TYPE TABS */
    .type-tabs {
        display: flex;
        justify-content: center;
        background: white;
        padding: 15px;
        gap: 15px;
        flex-wrap: wrap;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .type-tab {
        padding: 12px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f0f9fa;
        color: #097c87;
    }

    .type-tab:hover {
        background: #097c87;
        color: white;
    }

    /* HERO SECTION */
    .hero {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #097c87, #00bcd4);
        color: white;
    }

    .hero h1 {
        margin: 0 0 10px 0;
        font-size: 32px;
    }

    .hero p {
        margin: 0;
        opacity: 0.9;
    }

    /* LISTINGS */
    .listings-section {
        max-width: 1200px;
        margin: 10px auto;
        padding: 0 20px;
    }

    .section-title {
        color: #097c87;
        font-size: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }

    .listing-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .listing-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #eee;
    }

    .listing-content {
        padding: 20px;
    }

    .listing-title {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 18px;
        font-weight: 600;
    }

    .listing-price {
        font-size: 22px;
        font-weight: bold;
        color: #097c87;
        margin-bottom: 10px;
    }

    .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }

    .listing-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .listing-description {
        color: #555;
        font-size: 14px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .seller-badge,
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 10px;
        background: #f0f9fa;
        color: #097c87;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 10px;
        margin-left: 5px;
        background: #d4edda;
        color: #155724;
    }

    .unverified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 10px;
        margin-left: 5px;
        background: #dc3545;
        color: white;
    }

    .listing-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-buy-now {
        flex: 1;
        display: inline-block;
        padding: 10px 20px;
        background: #097c87;
        color: white;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-buy-now:hover {
        background: #065a63;
    }

    .btn-message {
        display: inline-block;
        padding: 10px 15px;
        background: #6c757d;
        color: white;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-message:hover {
        background: #5a6268;
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

    /* TABLET RESPONSIVE */
    @media (max-width: 900px) {

        .search-container {
            order: 3;
            width: 100%;
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
            order: 1;
        }
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {

        .hero {
            padding: 30px 15px;
        }

        .hero h1 {
            font-size: 26px;
        }

        .type-tabs {
            gap: 10px;
            padding: 10px;
        }

        .type-tab {
            padding: 10px 18px;
            font-size: 14px;
        }

        /* 2 CARDS PER ROW */
        .listings-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .listing-card {
            border-radius: 10px;
        }

        .listing-image {
            height: 140px;
        }

        .listing-content {
            padding: 12px;
        }

        .listing-title {
            font-size: 15px;
            margin-bottom: 6px;
        }

        .listing-price {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .listing-meta {
            font-size: 11px;
            gap: 6px;
        }

        .listing-description {
            font-size: 12px;
        }

        .seller-badge,
        .type-badge,
        .verified-badge,
        .unverified-badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .listing-actions {
            flex-direction: column;
            gap: 8px;
        }

        .btn-buy-now,
        .btn-message {
            width: 100%;
            padding: 10px;
            font-size: 13px;
        }

        .listings-section {
            padding: 0 10px;
        }
    }

    /* SMALL MOBILE */
    @media (max-width: 480px) {

        .listings-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .listing-image {
            height: 120px;
        }

        .listing-content {
            padding: 10px;
        }

        .listing-title {
            font-size: 14px;
        }

        .listing-price {
            font-size: 16px;
        }

        .listing-description {
            font-size: 11px;
        }

        .btn-buy-now,
        .btn-message {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="navbar">
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
    <input id="searchBar" placeholder="Search items..." type="text">
    <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')">&#9776;</button>
    <div class="nav-links" id="navLinks">
      <a href="index.php">Home</a>
      <a href="casualtraders.php">Casual</a>
      <a href="informaltraders.php">Informal</a>
      <a href="sell.php">Sell Item</a>
      <a href="mylistings.php">My Listings</a>
      <a href="messages.php">Messages</a>
      <a href="profile.php">Profile</a>
      <a href="auth/register.php" class="btn-signup" style="background: white; color: #097c87; padding: 8px 20px; border-radius: 20px; font-weight: 500; text-decoration: none;">Sign Up</a>
    </div>
  </div>
</nav>

<!-- SELLER TYPE TABS -->
<div class="type-tabs">
    <a href="casualtraders.php" class="type-tab">Casual Sellers</a>
    <a href="informaltraders.php" class="type-tab">Informal Traders</a>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Browse All Listings</h1>
    <p>Find the best deals from all sellers across GUMMY</p>
</div>

<!-- LISTINGS SECTION -->
<div class="listings-section">
    <h2 class="section-title">All Listings</h2>
    
    <div class="listings-grid" id="listingsGrid">
        <?php
        // Include database connection
        require_once 'db.php';
        
        // Get listings from database
      
         $stmt = $pdo->query("SELECT l.*, u.id_verified FROM listings l LEFT JOIN users u ON l.user_id = u.id WHERE l.status = 'active' ORDER BY l.created_at DESC");
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
            <p>No listings available yet</p>
            <p>Be the first to list an item!</p>
            <a href="sell.php">+ Sell an Item</a>
        </div>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
            <?php 
                $icon = '';
                $typeLabel = '';
                if ($listing['seller_type'] === 'casual') {
                    $typeLabel = 'Casual';
                } elseif ($listing['seller_type'] === 'informal') {
                    $typeLabel = 'Informal';
                } 
                
                // Get first image
                $imageSrc = '';
                if (!empty($listing['images'])) {
                    $imageSrc =  $listing['images'][0]['image_path'];
                }
            ?>
            <div class="listing-card <?php echo $listing['seller_type']; ?>">
                <?php if (!empty($imageSrc)): ?>
                    <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>" class="listing-image">
                <?php else: ?>
                    <div class="listing-image" style="display: flex; align-items: center; justify-content: center; font-size: 50px; color: #999;">&#128247;</div>
                <?php endif; ?>
                <div class="listing-content">
                    <h3 class="listing-title">
                        <a href="buy.php?listing_id=<?php echo $listing['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($listing['title']); ?>
                        </a>
                    </h3>
                    <div class="listing-price">R <?php echo number_format($listing['price'], 0, ',', ' '); ?></div>
                    <div class="listing-meta">
                        <span><?php echo htmlspecialchars($listing['location']); ?></span>
                        <span><?php echo date('M d, Y', strtotime($listing['created_at'])); ?></span>
                    </div>
                    <p class="listing-description"><?php echo htmlspecialchars($listing['description']); ?></p>
                    <?php if (!empty($listing['id_verified'])): ?>
                        <span class="seller-badge verified">Verified</span>
                    <?php else: ?>
                        <span class="seller-badge unverified">Unverified</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 GUMMY | Trading</p>
</div>

<script>
// Search functionality
document.getElementById('searchBar').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.listing-card');
    
    cards.forEach(card => {
        const title = card.querySelector('.listing-title').textContent.toLowerCase();
        const description = card.querySelector('.listing-description').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
