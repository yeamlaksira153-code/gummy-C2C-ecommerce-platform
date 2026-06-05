<aside style="padding:24px; border-right:1px solid rgba(11, 119, 132, 0.12); background:rgba(255,255,255,0.74); backdrop-filter:blur(10px);">
    <div style="margin-bottom:24px;">
        <div style="font-size:1.2rem; font-weight:800; margin-top:6px; color:#0b7784;">RBAC Console</div>
    </div>

    <nav style="display:grid; gap:10px;">
        <a class="btn secondary" href="dashboard.php">Dashboard</a>
        <?php if (current_user_can('view_users')): ?>
            <a class="btn secondary" href="users.php">Users</a>
        <?php endif; ?>
       
        <a class="btn secondary" href="logout.php">Logout</a>
    </nav>

    <div style="margin-top:24px; padding:16px; border-radius:16px; background:#f3f9fa; border:1px solid rgba(11,119,132,.12);">
        <div style="font-weight:700; margin-bottom:4px;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
        <div style="color:#60757b; font-size:0.92rem;">
            Role: <?php echo htmlspecialchars(implode(', ', current_user_roles())); ?>
        </div>
    </div>
</aside>
