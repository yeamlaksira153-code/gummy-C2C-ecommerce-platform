<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informal Traders - GUMMY Marketplace</title>
   <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        font-size:13px;
    }

    /* NAVBAR */
    .navbar {
        display: flex;
        align-items: center;
        background-color: #097c87;
        padding: 10px 20px;
        color: white;
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

    /* SEARCH BAR */
    #searchBar {
        flex: 1;
        width: 100%;
        max-width: 400px;
        min-height: 38px;
        border-radius: 20px;
        padding: 0 15px;
        margin: 0 20px;
        background-color: #ffffff;
        border: none;
        font-size: 14px;
    }

    /* NAV LINKS */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: auto;
        flex-wrap: wrap;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 15px;
        border-radius: 20px;
        transition: background 0.3s ease;
    }

    .nav-links a:hover {
        background: rgba(255,255,255,0.2);
    }

    /* MOBILE MENU */
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 5px 10px;
    }

    /* CATEGORIES */
    .categories {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        background-color: #ffffff;
        padding: 16px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .categories a {
        color: #333;
        text-decoration: none;
        font-size: 15px;
        padding: 8px 15px;
        border-radius: 20px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .categories a:hover,
    .categories a.active {
        background: #097c87;
        color: white;
    }

    /* HERO */
    .heroInformal {
        text-align: center;
        padding: 60px 20px;
        min-height: 300px;

        background:
            linear-gradient(
                135deg,
                rgba(9, 124, 135, 0.3),
                rgba(0, 188, 212, 0.2)
            ),
            url('../images/hero.jpg');

        background-size: cover;
        background-position: center;

        color: white;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .heroInformal h1 {
        margin: 0 0 10px 0;
        font-size: 36px;
    }

    .heroInformal p {
        margin: 0;
        font-size: 18px;
        opacity: 0.9;
    }

    /* TRUST INFO */
    .trust-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        padding: 20px;
        background: #f0f9fa;
        flex-wrap: wrap;
    }

    .trust-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #097c87;
        font-weight: 500;
    }

    /* LISTINGS */
    .listings-section {
        max-width: 1200px;
        margin: 10px auto;
        padding: 0 20px;
    }

    .section-title {
        color: #097c87;
        font-size: 24px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }

    .listing-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .listing-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #eee;
    }

    .listing-content {
        padding: 20px;
    }

    .listing-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
    }

    .listing-title {
        margin: 0;
        color: #333;
        font-size: 18px;
        font-weight: 600;
        flex: 1;
    }

    .trader-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #f0f9fa;
        color: #097c87;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .listing-price {
        font-size: 24px;
        font-weight: bold;
        color: #097c87;
        margin-bottom: 15px;
    }

    .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 15px;
        color: #666;
        margin-bottom: 15px;
    }

    .listing-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .listing-description {
        color: #555;
        font-size: 15px;
        line-height: 1.5;
        margin-bottom: 15px;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* TRADING OPTIONS */
    .trading-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }

    .option-tag {
        padding: 4px 10px;
        background: #f5f5f5;
        border-radius: 15px;
        font-size: 12px;
        color: #666;
    }

    .option-tag.available {
        background: #f0f9fa;
        color: #097c87;
    }

    .warranty-tag {
        padding: 4px 10px;
        background: #f0f9fa;
        color: #097c87;
        border-radius: 15px;
        font-size: 12px;
    }

    /* SELLER INFO */
    .seller-info {
        padding-top: 0px;
        border-top: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
    }

  

    .seller-name {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .experience-badge {
        font-size: 12px;
        color: #666;
    }

    .seller-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .seller-badge.verified {
        background: #d4edda;
        color: #155724;
    }

    .seller-badge.unverified {
        background: #dc3545;
        color: white;
    }

    /* ACTION BUTTONS */
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

    /* TABLET */
    @media (max-width: 900px) {

        #searchBar {
            order: 3;
            max-width: 100%;
            margin: 10px 0;
        }

        .menu-toggle {
            display: block;
            order: 2;
            margin-left: auto;
        }

        .logo {
            order: 1;
        }

        .nav-links {
            display: none;
            width: 100%;
            background-color: #097c87;
            text-align: center;
            padding: 15px 0;
            z-index: 1000;
            order: 4;
            flex-direction: column;
            margin-top: 10px;
            margin-left: 0;
        }

        .nav-links.active {
            display: flex;
        }

        .nav-links a {
            padding: 15px;
            width: 100%;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            border-radius: 0;
        }

        .categories {
            gap: 10px;
            padding: 12px;
        }
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .heroInformal {
            padding: 50px 15px;
            min-height: 240px;
        }

        .heroInformal h1 {
            font-size: 28px;
        }

        .heroInformal p {
            font-size: 15px;
        }

        .trust-info {
            gap: 15px;
            padding: 15px;
        }

        .trust-badge {
            font-size: 13px;
        }

        .categories {
            display: flex;
            overflow-x: auto;
            flex-wrap: nowrap;
            gap: 10px;
            padding: 10px;
            scrollbar-width: none;
        }

        .categories::-webkit-scrollbar {
            display: none;
        }

        .categories a {
            flex-shrink: 0;
            font-size: 13px;
            padding: 8px 14px;
        }

        .listings-section {
            padding: 0 10px;
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

        .listing-header {
            flex-direction: column;
            gap: 6px;
        }

        .listing-title {
            font-size: 15px;
        }

        .listing-price {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .listing-meta {
            gap: 6px;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .listing-description {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .trading-options {
            gap: 6px;
        }

        .option-tag,
        .warranty-tag {
            font-size: 10px;
            padding: 3px 8px;
        }

        .seller-info {
            gap: 8px;
        }

        .seller-avatar {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }

        .seller-name {
            font-size: 12px;
        }

        .experience-badge {
            font-size: 10px;
        }

        .seller-badge {
            font-size: 9px;
            padding: 3px 8px;
        }

        .listing-actions {
            flex-direction: column;
            gap: 8px;
        }

        .btn-buy-now,
        .btn-message {
            width: 100%;
            padding: 9px;
            font-size: 12px;
        }
    }

    /* SMALL MOBILE */
    @media (max-width: 480px) {

        .heroInformal h1 {
            font-size: 24px;
        }

        .heroInformal p {
            font-size: 14px;
        }

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
            font-size: 11px;
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
    <button class="menu-toggle" onclick="toggleBoth()">&#9776;</button>
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

<!-- CATEGORIES -->
<div class="categories" id="categories">
  <a href="#" class="active" onclick="filterCategory('all')">All</a>
  <a href="#" onclick="filterCategory('home')">Home</a>
  <a href="#" onclick="filterCategory('clothes')">Clothes</a>
  <a href="#" onclick="filterCategory('electronics')">Electronics</a>
  <a href="#" onclick="filterCategory('food')">Food</a>
  <a href="#" onclick="filterCategory('beauty')">Beauty</a>
  <a href="#" onclick="filterCategory('handmade')">Handmade</a>
  <a href="#" onclick="filterCategory('services')">Services</a>
  <a href="#" onclick="filterCategory('other')">Other</a>
</div>

<!-- TRUST INFO -->
<div class="trust-info">
    <div class="trust-badge">Local Traders</div>
    <div class="trust-badge">Support Small Business</div>
    <div class="trust-badge">Community Trading</div>
</div>

<!-- HERO -->
<div class="heroInformal">
    <h1>Informal Traders</h1>
    <p>Find great items from hardworking informal traders.</p>
</div>
<!-- LISTINGS SECTION -->
<div class="listings-section">
    <div class="listings-grid" id="listingsGrid">
        <?php
        require_once 'db.php';
        
        $stmt = $pdo->prepare("SELECT l.*, u.id_verified FROM listings l LEFT JOIN users u ON l.user_id = u.id WHERE l.seller_type = 'informal' AND l.status = 'active' ORDER BY l.created_at DESC");
        $stmt->execute();
        $listings = $stmt->fetchAll();
        
        foreach ($listings as &$listing) {
            $imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
            $imgStmt->execute([$listing['id']]);
            $listing['images'] = $imgStmt->fetchAll();
        }
        unset($listing);
        
        if (empty($listings)):
        ?>
        <div class="empty-state" style="grid-column: 1 / -1;">
            <p>No listings available yet</p>
            <a href="sell.php">+ Sell as an Informal Trader</a>
        </div>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
            <?php 
                $traderName = $listing['trader_name'] ?? 'Trader';
                $imageSrc = '';
                if (!empty($listing['images']) && isset($listing['images'][0]['image_path'])) {
                    $imageSrc = $listing['images'][0]['image_path'];
                }
            ?>
            <div class="listing-card" data-category="<?php echo htmlspecialchars($listing['category']); ?>">
                <?php if (!empty($imageSrc)): ?>
                    <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>" class="listing-image">
                <?php else: ?>
                    <div class="listing-image" style="display: flex; align-items: center; justify-content: center; font-size: 50px; color: #999; background: #f0f0f0;">📷</div>
                <?php endif; ?>

                <div class="listing-content">
                    <h3 class="listing-title">
                        <a href="buy.php?listing_id=<?php echo $listing['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($listing['title']); ?>
                        </a>
                    </h3>
                    
                    <div class="listing-price">R <?php echo number_format($listing['price'], 0, ',', ' '); ?></div>
                    
                    <div class="listing-meta" style="margin-bottom: 8px;">
                        <span><?php echo htmlspecialchars($listing['location']); ?></span>
                        <span><?php echo date('M d, Y', strtotime($listing['created_at'])); ?></span>
                    </div>

                    <p class="listing-description" style="margin-bottom: 12px;"><?php echo htmlspecialchars($listing['description']); ?></p>
                    
                    <!-- Trading Options  -->
                    <div class="trading-options" style="margin-bottom: 12px; display: flex; gap: 5px;">
                        <?php if (!empty($listing['delivery_options'])): ?>
                            <?php $deliveryLabels = ['pickup' => 'Meetup', 'delivery' => 'Delivery', 'both' => 'Both']; ?>
                            <span class="option-tag available" style="font-size: 11px; background: #e8f4f8; padding: 4px 8px; border-radius: 4px;"><?php echo $deliveryLabels[$listing['delivery_options']] ?? $listing['delivery_options']; ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($listing['warranty']) && $listing['warranty'] !== 'none'): ?>
                            <?php $warrantyLabels = ['seller_guarantee' => 'Guarantee', 'days_7' => '7 Days', 'days_14' => '14 Days', 'days_30' => '30 Days']; ?>
                            <span class="warranty-tag" style="font-size: 11px; background: #fff3cd; padding: 4px 8px; border-radius: 4px;"><?php echo $warrantyLabels[$listing['warranty']] ?? $listing['warranty']; ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Clean Seller Info -->
                    <div class="seller-info" style="display: flex; align-items: center; justify-content: space-between; padding-top: 5px;">
                        <span class="seller-name" style="font-weight: bold; color: #333; font-size: 14px;">
                            <?php echo htmlspecialchars($traderName); ?>
                        </span>
                        
                        <?php if (!empty($listing['id_verified']) && $listing['id_verified'] == 1): ?>
                            <span class="seller-badge verified" style="font-size: 11px; color: #237790; font-weight: light;"> Verified</span>
                        <?php else: ?>
                            <span class="seller-badge unverified" style="font-size: 11px; color: #999;">Unverified</span>
                        <?php endif; ?>
                    </div>
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
function toggleBoth() {
    document.getElementById('navLinks').classList.toggle('active');
    document.getElementById('categories').classList.toggle('active');
}

document.addEventListener('click', function(event) {
    var navLinks = document.getElementById('navLinks');
    var categories = document.getElementById('categories');
    var menuToggle = document.querySelector('.menu-toggle');

    if ((navLinks.classList.contains('active') || categories.classList.contains('active')) &&
        !navLinks.contains(event.target) &&
        !categories.contains(event.target) &&
        !menuToggle.contains(event.target)) {

        navLinks.classList.remove('active');
        categories.classList.remove('active');
    }
});

function filterCategory(category) {
    // Update active category
    document.querySelectorAll('.categories a').forEach(link => {
        link.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Filter cards
    const cards = document.querySelectorAll('.listing-card');
    cards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Close mobile menu
    document.getElementById('categories').classList.remove('active');
}

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
