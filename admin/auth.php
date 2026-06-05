<?php

require_once __DIR__ . '/../middleware/role.php';

requireLogin('../auth/adminlogin.php');
auth_sync_current_user($pdo);
requireRole(['admin', 'manager', 'support'], 'Access denied.');

