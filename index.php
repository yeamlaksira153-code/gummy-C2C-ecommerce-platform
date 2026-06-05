<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>GUMMY Marketplace</title>

  <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

   /* Main Navbar Container */
.main-navbar {
    background-color: #097c87; 
    padding: 10px 15px;
    position: relative;
}

.navbar-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.logo-img {
    height: 40px; /* Adjust as needed */
    display: block;
}

.search-container {
    flex-grow: 1;
    max-width: 500px;
}

.search-container input {
    width: 100%;
    padding: 8px 12px;
    border-radius: 20px;
    border: none;
}

.nav-links {
    display: flex;
    gap: 15px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 300;
}

.menu-toggle {
    display: none; 
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
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

/* --- RESPONSIVE MOBILE VIEW --- */
@media (max-width: 768px) {
    .menu-toggle {
        display: block; 
    }

    .nav-links {
        display: none; /* Hide links by default */
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #333;
        padding: 20px;
        z-index: 1000;
    }

    /* Class added by JS to open menu */
    .nav-links.active {
        display: flex;
    }

    .search-container {
        order: 2;
    }
}


    /* AUTH BUTTONS */
    .auth-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-signin {
        background: transparent;
        border: 2px solid white;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-signin:hover {
        background: white;
        color: #097c87;
    }

    .btn-signup {
        background: white;
        color: #097c87;
        border: 2px solid white;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-signup:hover {
        background: #f0f0f0;
    }

    /* USER BADGE */
    .user-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        padding: 5px 12px;
        border-radius: 20px;
        color: white;
    }

    .verified-badge-small {
        background: #4caf50;
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
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

    /* HERO SECTION */
    .hero {
        text-align: center;
        padding: 80px 20px;
        min-height: 300px;
        background-image: url('../images/hero22.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    .hero h1 {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .hero p {
        color: #fafafa;
        font-size: 18px;
        font-weight: bold;
    }

    /* MARKETPLACE SECTIONS */
    .marketplace-sections {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .section-title {
        color: #097c87;
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
    }

    .trading-options {
        display: flex;
        gap: 25px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .option-card {
        background: white;
        border-radius: 15px;
        padding: 40px 30px;
        width: 320px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        border: 3px solid #097c87;
    }

    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        background: #f0f9fa;
    }

    .option-card .icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .option-card h2 {
        color: #097c87;
        margin: 0 0 15px 0;
        font-size: 24px;
    }

    .option-card p {
        color: #333;
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .option-card .btn {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 30px;
        background: #097c87;
        color: white;
        border-radius: 25px;
        font-weight: 500;
    }

    /* HOW IT WORKS */
    .how-it-works {
        background: white;
        padding: 60px 20px;
        margin-top: 40px;
    }

    .how-it-works-content {
        max-width: 1000px;
        margin: 0 auto;
    }

    .steps {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        margin-top: 40px;
    }

    .step {
        text-align: center;
        width: 200px;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: #097c87;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        margin: 0 auto 15px;
    }

    .step h3 {
        color: #097c87;
        margin-bottom: 10px;
    }

    .step p {
        color: #333;
        font-size: 14px;
    }

    /* CTA SECTION */
    .cta-section {
        background: linear-gradient(135deg, #097c87, #00bcd4);
        color: white;
        text-align: center;
        padding: 60px 20px;
        margin-top: 40px;
    }

    .cta-section h2 {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .cta-section p {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 30px;
    }

    .cta-btn {
        display: inline-block;
        padding: 15px 40px;
        background: white;
        color: #097c87;
        text-decoration: none;
        border-radius: 30px;
        font-weight: bold;
        font-size: 16px;
    }

    /* LISTINGS SECTION */
    .listings-section {
        max-width: 1200px;
        margin: 10px auto;
        padding: 0 20px;
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
        cursor: pointer;
    }

    .listing-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .listing-content {
        padding: 20px;
    }

    .listing-title {
        margin: 0 0 10px 0;
        font-size: 18px;
        color: #333;
    }

    .listing-price {
        font-size: 22px;
        font-weight: bold;
        color: #097c87;
        margin-bottom: 10px;
    }

    .listing-meta {
        display: flex;
        gap: 15px;
        color: #666;
        font-size: 13px;
        margin-bottom: 10px;
        flex-wrap: wrap;
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
        color: #237790;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        grid-column: 1 / -1;
    }

    .empty-state .icon {
        font-size: 50px;
        margin-bottom: 15px;
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

        .search-container {
            order: 3;
            max-width: 100%;
            width: 100%;
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

        .auth-buttons {
            order: 2;
            margin-left: auto;
        }

        .menu-toggle {
            display: block;
            order: 1;
        }

        .logo {
            order: 0;
        }

        .hero h1 {
            font-size: 38px;
        }
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .hero {
            padding: 60px 15px;
            min-height: 250px;
        }

        .hero h1 {
            font-size: 30px;
        }

        .hero p {
            font-size: 15px;
        }

        .marketplace-sections {
            padding: 0 10px;
        }

        .section-title {
            font-size: 24px;
        }

        .trading-options {
            gap: 15px;
        }

        .option-card {
            width: 100%;
            padding: 30px 20px;
        }

        .steps {
            gap: 25px;
        }

        .step {
            width: 100%;
            max-width: 260px;
        }

        .cta-section h2 {
            font-size: 26px;
        }

        .cta-section p {
            font-size: 15px;
        }

        /* 2 CARDS PER ROW */
        .listings-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
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

        .seller-badge,
        .type-badge,
        .verified-badge,
        .unverified-badge {
            font-size: 10px;
            padding: 3px 8px;
        }
    }

    /* SMALL MOBILE */
    @media (max-width: 480px) {

        .hero h1 {
            font-size: 26px;
        }

        .hero p {
            font-size: 14px;
        }

        .auth-buttons {
            flex-direction: column;
            gap: 5px;
            width: 100%;
        }

        .btn-signin,
        .btn-signup {
            width: 100%;
            text-align: center;
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
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
</head>
<body>
<!-- NAVBAR -->
<nav class="main-navbar">
    <div class="navbar-top">
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>

        <!-- SEARCH BAR -->
        <div class="search-container">
            <input id="searchBar" placeholder="Search item..." type="text" />
        </div>

        <!-- MOBILE TOGGLE BUTTON -->
        <button class="menu-toggle" onclick="toggleMenu()" aria-label="Toggle Menu">☰</button>

        <!-- NAV LINKS (Only these go inside the mobile menu) -->
        <div class="nav-links" id="navLinks">
            <a href="index.php">Home</a>
      <a href="casualtraders.php">Casual</a>
      <a href="informaltraders.php">Informal</a>
      <a href="sell.php">Sell Item</a>
      <a href="mylistings.php">My Listings</a>
      <a href="messages.php">Messages</a>
      <a href="profile.php">Profile</a>
      <a href="auth/login.php">login</a>

        </div>
    </div>
</nav>

<!-- CATEGORIES (White & Sideways Scrollable) -->
<div class="categories" id="categories">
    <a href="#" class="active" onclick="filterCategory('all')">All</a>
    <a href="#" onclick="filterCategory('home')">Home & Garden</a>
    <a href="#" onclick="filterCategory('clothes')">Clothes & Fashion</a>
    <a href="#" onclick="filterCategory('electronics')">Electronics</a>
    <a href="#" onclick="filterCategory('vehicles')">Vehicles</a>
    <a href="#" onclick="filterCategory('sports')">Sports & outdoors</a>
    <a href="#" onclick="filterCategory('toys')">Toys & Games</a>
    <a href="#" onclick="filterCategory('food')">Food</a>
    <a href="#" onclick="filterCategory('beauty')">Beauty</a>
    <a href="#" onclick="filterCategory('handmade')">Handmade</a>
    <a href="#" onclick="filterCategory('services')">Services</a>
    <a href="#" onclick="filterCategory('other')">Other</a>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Welcome to GUMMY</h1>
    <p>Trade with confidence in a safe, supportive community designed for easy,convenient buying and selling</p>
</div>

<!-- ALL LISTINGS SECTION -->
<div class="listings-section">
    <div class="listings-grid" id="listingsGrid">
        <?php
        require_once 'db.php';
        
        $stmt = $pdo->query("SELECT l.*, u.id_verified FROM listings l LEFT JOIN users u ON l.user_id = u.id WHERE l.status = 'active' ORDER BY l.created_at DESC");
        $listings = $stmt->fetchAll();
        
        foreach ($listings as &$listing) {
            $imgStmt = $pdo->prepare("SELECT image_path FROM listing_images WHERE listing_id = ? LIMIT 1");
            $imgStmt->execute([$listing['id']]);
            $listing['images'] = $imgStmt->fetchAll();
        } 
        unset($listing);
        
        if (empty($listings)):
        ?>
        <div class="empty-state">
            <p>No listings available yet</p>
            <p>Be the first to list an item!</p>
            <a href="sell.php" style="color: #097c87; font-weight: 600; text-decoration: none;">+ Sell an Item</a>
        </div>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
            <?php 
                $imageSrc = '';
                if (!empty($listing['images']) && isset($listing['images'][0]['image_path'])) {
                    $imageSrc = htmlspecialchars($listing['images'][0]['image_path']);
                }

                //  Normalizing category names to lowercase for the JavaScript filter
                $categorySlug = strtolower(str_replace([' ', '&'], '', $listing['category']));
            ?>
            
            <!--  Added data-category attribute here -->
            <div class="listing-card" data-category="<?php echo $categorySlug; ?>">
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

<!-- MARKETPLACE SECTIONS -->
<div class="marketplace-sections">
    <h2 class="section-title">Start Trading Today</h2>
    
    <div class="trading-options">
        <a href="casualtraders.php" class="option-card">
            <h2>Casual Sellers</h2>
            <p>Buy and sell personal items from people in your area. Quick and easy transactions.</p>
            <span class="btn">Browse Casual</span>
        </a>
        
        <a href="informaltraders.php" class="option-card">
            <h2>Informal Traders</h2>
            <p>Support local unregistered traders selling goods for a living. Community-powered marketplace.</p>
            <span class="btn">Browse Traders</span>
        </a>
        
    
    </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-it-works">
    <div class="how-it-works-content">
        <h2 class="section-title" style="margin-bottom: 10px;">How It Works</h2>
        
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Sign Up</h3>
                <p>Create an account and verify with your ID</p>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <h3>Choose Type</h3>
                <p>Select casual or informal</p>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <h3>Browse & Buy</h3>
                <p>Find items and contact sellers</p>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <h3>Complete Trade</h3>
                <p>Meet up or arrange delivery</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA SECTION -->
<div class="cta-section">
    <h2>Ready to Start Selling?</h2>
    <p>Sign up, verify your ID, and start trading today</p>
    <a href="auth/register.php" class="cta-btn">Sign Up Now</a>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 GUMMY | Trading</p>
</div>

<script>


function toggleMenu() {
    var navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    var navLinks = document.getElementById('navLinks');
    var menuToggle = document.querySelector('.menu-toggle');
    if (navLinks.classList.contains('active') && 
        !navLinks.contains(event.target) && 
        !menuToggle.contains(event.target)) {
        navLinks.classList.remove('active');
    }
});


// 1. SEARCH FUNCTIONALITY
const searchBar = document.getElementById('searchBar');
if (searchBar) {
    searchBar.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            window.location.href = 'alllistings.php?search=' + encodeURIComponent(this.value);
        }
    });

    searchBar.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.listing-card');
        cards.forEach(card => {
            const title = card.innerText.toLowerCase();
            card.style.display = title.includes(searchTerm) ? 'block' : 'none';
        });
    });
}

// 2. NAVIGATION TOGGLE
window.toggleMenu = function() {
    const navLinks = document.getElementById('navLinks');
    if (navLinks) {
        navLinks.classList.toggle('active');
    }
};

// 3. CATEGORY FILTERING
window.filterCategory = function(category) {
    console.log("Filtering by:", category);
    
    // Update active UI state
    const links = document.querySelectorAll('.categories a');
    links.forEach(link => link.classList.remove('active'));
    
    // Add active class to clicked element
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }

    // Actual filtering logic for listing cards
    const cards = document.querySelectorAll('.listing-card');
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category'); 
        if (category === 'all' || cardCategory === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};

// 4. CLICK OUTSIDE TO CLOSE
document.addEventListener('click', function(event) {
    const navLinks = document.getElementById('navLinks');
    const menuToggle = document.querySelector('.menu-toggle');

    // If menu is open and user clicks outside of it and the toggle button
    if (navLinks && navLinks.classList.contains('active')) {
        if (!navLinks.contains(event.target) && !menuToggle.contains(event.target)) {
            navLinks.classList.remove('active');
        }
    }
});

</script>

</body>
</html>
