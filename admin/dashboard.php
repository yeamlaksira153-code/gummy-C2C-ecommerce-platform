<?php
require_once __DIR__ . '/../middleware/role.php';
require_once __DIR__ . '/auth.php';

requireRole(['admin', 'manager', 'support'], 'Access denied.');

$pageTitle = 'Admin Dashboard';
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/sidebar.php';
?>
<main class="admin-main">
    <section class="admin-panel">
        <div class="page-header">
            <h1><?php echo current_user_has_role('admin') ? 'Admin Dashboard' : (current_user_has_role('manager') ? 'Manager Dashboard' : 'Support Dashboard'); ?></h1>
        </div>

        <div class="content-wrap">
            <div class="grid cols-2">
                <div class="card">
                    <h2 style="margin:12px 0 8px;">Manage users safely</h2>
                    <p class="muted">View all users, approve identity checks, and manage roles.</p>
                    <div class="actions">
                        <a class="btn" href="users.php">Open users</a>
                    </div>
                    
                </div>
                
                <div class="card">
                    <h2 style="margin:12px 0 8px;">Manage Listings</h2>
                    <div class="actions">
                        <a class="btn" href="listings.php">listings</a>
                    </div>
                    
                </div>


                
                </div>
            </div>
        </div>
    </section>
</main>
</div>
</body>
</html>
