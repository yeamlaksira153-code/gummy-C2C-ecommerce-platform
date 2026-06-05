<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casual Sellers - GUMMY Marketplace</title>
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
        gap: 15px;
        flex-wrap: wrap;
        margin-left: auto;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 15px;
        border-radius: 20px;
        transition: background 0.3s;
    }

    .nav-links a:hover {
        background: rgba(255,255,255,0.2);
    }

    /* MOBILE MENU BUTTON */
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 5px 10px;
    }

   /* --- SECONDARY NAV (CATEGORIES) --- */
.categories {
    background-color: white;
    border-bottom: 1px solid #eee;
    display: flex; 
    overflow-x: auto; 
    white-space: nowrap; 
    padding: 12px 10px;
    gap: 10px; 
    -webkit-overflow-scrolling: touch; 
}

/* Hide scrollbar  */
.categories::-webkit-scrollbar {
    display: none;
}

.categories a {
    flex: 0 0 auto; 
    padding: 8px 18px;
    text-decoration: none;
    color: #555;
    font-size: 14px;
    border-radius: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    transition: all 0.2s;
}

.categories a.active {
    background-color: #097c87;
    color: white;
    border-color: #097c87;
}


    /* HERO SECTION */
    .heroCasual {
        text-align: center;
        padding: 60px 20px;
        min-height: 300px;
        background:
            linear-gradient(
                135deg,
                rgba(9, 124, 135, 0.3),
                rgba(0, 188, 212, 0.2)
            ),
            url('../images/casualhero.jpg');

        background-size: cover;
        background-position: center;
        color: white;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .heroCasual h1 {
        margin: 0 0 10px 0;
        font-size: 36px;
    }

    .heroCasual p {
        margin: 0;
        font-size: 18px;
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
        font-size: 24px;
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
        margin: 10px 0;
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
        margin-bottom: 15px;
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
        margin-bottom: 15px;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
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

    .listing-condition {
        display: inline-block;
        padding: 3px 8px;
        background: #f5f5f5;
        border-radius: 5px;
        font-size: 12px;
        color: #666;
        margin-left: 10px;
    }

    .negotiable-badge {
        display: inline-block;
        padding: 3px 8px;
        background: #f0f9fa;
        border-radius: 5px;
        font-size: 11px;
        color: #097c87;
        margin-left: 5px;
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

/* --- SECONDARY NAV (CATEGORIES) --- */
.categories {
    background-color: white;
    border-bottom: 1px solid #eee;
    display: flex;
    overflow-x: auto; /* Side scroll */
    white-space: nowrap;
    padding: 12px 10px;
    -webkit-overflow-scrolling: touch;
}

/* Hide scrollbar but keep functionality */
.categories::-webkit-scrollbar {
    display: none;
}

.categories a {
    flex-shrink: 0; 
    padding: 6px 15px;
    margin-right: 10px;
    text-decoration: none;
    color: #555;
    font-size: 14px;
    border-radius: 15px;
    border: 1px solid #ddd;
}

.categories a.active {
    background-color: #097c87;
    color: white;
    border-color: white;
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


    /* MOBILE */
    @media (max-width: 768px) {

        .heroCasual {
            padding: 50px 15px;
            min-height: 240px;
        }

        .heroCasual h1 {
            font-size: 28px;
        }

        .heroCasual p {
            font-size: 15px;
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
            margin: 6px 0;
        }

        .listing-price {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .listing-meta {
            font-size: 11px;
            gap: 6px;
            margin-bottom: 10px;
        }

        .listing-description {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .listing-condition,
        .negotiable-badge {
            font-size: 10px;
        }

        .seller-badge {
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
            padding: 9px;
            font-size: 12px;
        }
    }

    /* SMALL MOBILE */
    @media (max-width: 480px) {

        .heroCasual h1 {
            font-size: 24px;
        }

        .heroCasual p {
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
  <a href="#" onclick="filterCategory('home')">Home & and Garden </a>
  <a href="#" onclick="filterCategory('clothes')">Clothes & Fashion</a>
  <a href="#" onclick="filterCategory('electronics')">Electronics</a>
  <a href="#" onclick="filterCategory('vehicles')">Vehicles</a>
  <a href="#" onclick="filterCategory('sports')">Sports & outdoors</a>
  <a href="#" onclick="filterCategory('toys')">Toys & Games</a>
  <a href="#" onclick="filterCategory('other')">Other</a>
</div>

<!-- HERO -->
<div class="heroCasual">
    <h1>Casual Sellers</h1>
    <p>Explore unique items from casual sellers in your community, offering pre-loved and unused goods</p>
</div>

<!-- LISTINGS SECTION -->
<div class="listings-section"> 
    <div class="listings-grid" id="listingsGrid">
        <?php
        // Include database connection
        require_once 'db.php';
        
        // Get casual listings from database with user verification status
       $stmt = $pdo->prepare("
            SELECT l.*, u.id_verified 
            FROM listings l 
            LEFT JOIN users u ON l.user_id = u.id 
            WHERE l.seller_type = 'casual' AND l.status = 'active' 
            ORDER BY l.created_at DESC
        ");
        $stmt->execute();
        $listings = $stmt->fetchAll();
        
        // Get images for each listing
        foreach ($listings as &$listing) {
            $imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
            $imgStmt->execute([$listing['id']]);
            $listing['images'] = $imgStmt->fetchAll();
             unset($listing);
        }
        
        if (empty($listings)):
        ?>
        <div class="empty-state" style="grid-column: 1 / -1;">
            <p>No casual listings available yet</p>
            <p>Be the first to list an item!</p>
            <a href="sell.php">+ Sell an Item</a>
        </div>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
            <?php 
                //  retrieve the first image path from the database query
                $imageSrc = '';
                if (!empty($listing['images']) && isset($listing['images'][0]['image_path'])) {
                    $imageSrc = $listing['images'][0]['image_path'];
                }
            ?>


            <div class="listing-card" data-category="<?php echo $listing['category']; ?>">
                <?php if (!empty($listing['images'])): ?>
                    <img src="<?php echo $listing['images'][0]['image_path']; ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>" class="listing-image">
                    <!-- Debug: <?php echo htmlspecialchars($listing['images'][0]['image_path']); ?> -->
                <?php else: ?>
                    <div class="listing-image" style="display: flex; align-items: center; justify-content: center; font-size: 50px; color: #999;">
                        No image (Listing ID: <?php echo $listing['id']; ?>)
                    </div>
                <?php endif; ?>
                <div class="listing-content">
                    <h3 class="listing-title">
                        <a href="buy.php?listing_id=<?php echo $listing['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($listing['title']); ?>
                        </a>
                        <span class="listing-condition"><?php echo ucfirst(str_replace('_', ' ', $listing['condition'] ?? 'good')); ?></span>
                        <?php if (isset($listing['negotiable']) && $listing['negotiable'] === 'yes'): ?>
                            <span class="negotiable-badge">Negotiable</span>
                        <?php endif; ?>
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
