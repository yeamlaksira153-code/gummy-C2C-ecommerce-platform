<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Admin Panel';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        :root {
            --bg: #eef4f5;
            --panel: #ffffff;
            --panel-2: #f7fbfc;
            --text: #193238;
            --muted: #60757b;
            --brand: #0b7784;
            --brand-2: #054b55;
            --line: rgba(11, 119, 132, 0.14);
            --shadow: 0 18px 50px rgba(8, 44, 50, 0.08);
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Segoe UI, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(11, 119, 132, 0.14), transparent 28%),
                radial-gradient(circle at bottom right, rgba(5, 75, 85, 0.10), transparent 26%),
                var(--bg);
        }

        a { color: inherit; }

        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
        }

        .admin-main {
            padding: 28px;
        }

        .admin-panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .page-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(135deg, rgba(11, 119, 132, 0.06), rgba(255, 255, 255, 0.92));
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 1.6rem;
        }

        .page-header p {
            margin: 0;
            color: var(--muted);
        }

        .content-wrap {
            padding: 24px;
        }

        .card {
            background: var(--panel-2);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid.cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .muted { color: var(--muted); }

        .btn, button {
            appearance: none;
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            padding: 10px 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn.secondary {
            background: #dceff2;
            color: var(--brand-2);
        }

        .btn.danger {
            background: #b63b3b;
        }

              input, select, textarea {
            width: 100%;
            border: 1px solid #c8d8db;
            border-radius: 10px;
            padding: 10px 12px;
            font: inherit;
            background: #fff;
        }

        /* Responsive table wrapper for horizontal scrolling */
        div[style*="overflow-x"] {
            border-radius: 14px;
            border: 1px solid var(--line);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--brand) transparent;
        }

        div[style*="overflow-x"]::-webkit-scrollbar {
            height: 8px;
        }

        div[style*="overflow-x"]::-webkit-scrollbar-track {
            background: transparent;
        }

        div[style*="overflow-x"]::-webkit-scrollbar-thumb {
            background: var(--brand);
            border-radius: 4px;
        }

        div[style*="overflow-x"]::-webkit-scrollbar-thumb:hover {
            background: var(--brand-2);
        }

        table {
            width: 100%;
            min-width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: none;
            border-radius: 0;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #e6eef0;
            white-space: nowrap;
        }

        th {
            background: #f2f7f8;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e8f4f6;
            color: var(--brand-2);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .status-ok { background: #e5f5e9; color: #17633a; }
        .status-warn { background: #fff3d9; color: #8d6100; }
        .status-muted { background: #edf1f3; color: #61737a; }

        .form-row {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .notice {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            background: #eef8fa;
            color: #1f5660;
        }

        .notice.error {
            background: #fbe9ea;
            color: #8b2c32;
        }

        @media (max-width: 960px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-main { padding: 18px; }
        }

        @media (max-width: 720px) {
            .grid.cols-2, .form-row { grid-template-columns: 1fr; }
            .content-wrap { padding: 18px; }
        }
    </style>
</head>
<body>
<div class="admin-shell">
