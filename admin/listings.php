<?php
// Include middleware to secure the page
require_once __DIR__ . '/../middleware/role.php';

// Authenticate and check for admin/manager roles
requireLogin('../auth/adminlogin.php');
auth_sync_current_user($pdo);
requireRole(['admin', 'manager'], 'Access denied.');

// Fetch all listings from the database
try {
    $stmt = $pdo->query("
        SELECT l.*, u.full_name as seller_name, 
        (SELECT image_path FROM listing_images WHERE listing_id = l.id LIMIT 1) as main_image
        FROM listings l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.created_at DESC
    ");
    $allListings = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching listings: " . $e->getMessage());
}

$pageTitle = 'Manage Listings';
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/sidebar.php';
?>

<div class="main-content" style="padding: 20px;">
    <h1>All Marketplace Listings</h1>
    <p>View and manage listings.</p>
<div class=table-responsive>
    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <thead style="background: #097c87; color: black;">
            <tr>
                <th style="padding: 12px; text-align: left;">Image</th>
                <th style="padding: 12px; text-align: left;">Title</th>
                <th style="padding: 12px; text-align: left;">Seller</th>
                <th style="padding: 12px; text-align: left;">Price</th>
                <th style="padding: 12px; text-align: left;">Status</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
              <tbody>
            <?php foreach ($allListings as $list): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">
                    <?php if (!empty($list['main_image'])): ?>
                        <img src="../<?php echo htmlspecialchars($list['main_image']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <div style="width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px;">📷</div>
                    <?php endif; ?>
                </td>
                <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($list['title']); ?></td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($list['seller_name'] ?? 'Unknown'); ?></td>
                <td style="padding: 10px;">R <?php echo number_format($list['price'], 2); ?></td>
                <td style="padding: 10px;">
                    <span class="status-badge" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; background: <?php echo ($list['status'] === 'active') ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo ($list['status'] === 'active') ? '#155724' : '#721c24'; ?>;">
                        <?php echo ucfirst($list['status']); ?>
                    </span>
                </td>
                <td style="padding: 10px;">
                    <?php 
                  
                    $userRole = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
                    if ($userRole === 'admin'): ?>
                        <a href="edit_listing.php?id=<?php echo $list['id']; ?>" style="color: #097c87; text-decoration: none; margin-right: 10px;">Edit</a>
                        <a href="delete_listing.php?id=<?php echo $list['id']; ?>" style="color: #d9534f; text-decoration: none;" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php else: ?>
                        <span style="color: #667; font-style: italic;">View Only</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?> 
        </tbody>
    </table>
    </div>
</div>
