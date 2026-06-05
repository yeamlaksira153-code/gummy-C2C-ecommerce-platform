<?php
require_once __DIR__ . '/../middleware/role.php';

// Authenticate
requireLogin('../auth/adminlogin.php');
auth_sync_current_user($pdo);
requireRole(['admin', 'manager'], 'Access denied.');

$listing_id = $_GET['id'] ?? 0;
$message = '';

//  Fetch current listing data
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

if (!$listing) {
    die("Listing not found.");
}

//  Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    try {
        $update = $pdo->prepare("UPDATE listings SET title = ?, price = ?, status = ?, description = ? WHERE id = ?");
        $update->execute([$title, $price, $status, $description, $listing_id]);
        $message = "Listing updated successfully!";
        
        // Refresh data
        $listing['title'] = $title;
        $listing['price'] = $price;
        $listing['status'] = $status;
        $listing['description'] = $description;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$pageTitle = 'Edit Listing';
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/sidebar.php';
?>

<div class="main-content" style="padding: 20px;">
    <h1>Edit Listing #<?php echo $listing_id; ?></h1>
    <a href="listings.php" style="color: #097c87; text-decoration: none;">← Back to Listings</a>

    <?php if ($message): ?>
        <div style="padding: 15px; background: #d4edda; color: #000; border-radius: 5px; margin: 20px 0;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px; max-width: 600px;">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Product Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Price (R)</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($listing['price']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Status</label>
            <select name="status" style="width: 100%; padding: 10px; border: 1px solid #000; border-radius: 5px;">
                <option value="active" <?php echo $listing['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="sold" <?php echo $listing['status'] == 'sold' ? 'selected' : ''; ?>>Sold</option>
                <option value="removed" <?php echo $listing['status'] == 'removed' ? 'selected' : ''; ?>>Removed</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Description</label>
            <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($listing['description']); ?></textarea>
        </div>

        <button type="submit" style="background: #097c87; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Update Listing</button>
    </form>
</div>
