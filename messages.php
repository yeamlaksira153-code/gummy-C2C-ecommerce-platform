<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php?redirect=messages.php');
    exit;
}

$current_user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$listing_id = $_GET['listing_id'] ?? null;
$seller_id = $_GET['seller_id'] ?? null;

// Handle sending new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'] ?? 0;
    $listing_id = $_POST['listing_id'] ?? null;
    $message_text = trim($_POST['message'] ?? '');
    
    if ($receiver_id && $message_text) {
        // Check if conversation already exists
        $checkStmt = $pdo->prepare("
            SELECT id FROM messages 
            WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))
            AND listing_id = ?
            ORDER BY created_at DESC LIMIT 1
        ");
        $checkStmt->execute([$current_user_id, $receiver_id, $receiver_id, $current_user_id, $listing_id]);
        $existing_conversation = $checkStmt->fetch();
        
        // Insert new message
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, listing_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$current_user_id, $receiver_id, $listing_id, $message_text]);
    }
}

// Get conversations (distinct conversations)
$conversationsStmt = $pdo->prepare("
    SELECT 
        CASE 
            WHEN m.sender_id = ? THEN m.receiver_id 
            ELSE m.sender_id 
        END as other_user_id,
        m.listing_id,
        l.title as listing_title,
        u.full_name as other_user_name,
        (SELECT message FROM messages WHERE 
            ((sender_id = ? AND receiver_id = other_user_id) OR (sender_id = other_user_id AND receiver_id = ?))
            AND listing_id = m.listing_id
            ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM messages WHERE 
            ((sender_id = ? AND receiver_id = other_user_id) OR (sender_id = other_user_id AND receiver_id = ?))
            AND listing_id = m.listing_id
            ORDER BY created_at DESC LIMIT 1) as last_message_time,
        (SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND sender_id = other_user_id AND read_status = 'unread') as unread_count
    FROM messages m
    JOIN users u ON u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END
    LEFT JOIN listings l ON l.id = m.listing_id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    GROUP BY other_user_id, m.listing_id, u.full_name, l.title
    ORDER BY last_message_time DESC
");
$conversationsStmt->execute([$current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id]);
$conversations = $conversationsStmt->fetchAll();

// Get messages for a specific conversation
$selected_conversation = null;
$chat_messages = [];
if ($action === 'chat' && isset($_GET['user_id']) && isset($_GET['listing_id'])) {
    $other_user_id = $_GET['user_id'];
    $chat_listing_id = $_GET['listing_id'];
    
    // Get other user info
    $userStmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
    $userStmt->execute([$other_user_id]);
    $other_user = $userStmt->fetch();
    
    // Get listing info
    $listingStmt = $pdo->prepare("SELECT title, price FROM listings WHERE id = ?");
    $listingStmt->execute([$chat_listing_id]);
    $chat_listing = $listingStmt->fetch();
    
    // Get messages
    $messagesStmt = $pdo->prepare("
        SELECT m.*, u.full_name as sender_name 
        FROM messages m
        JOIN users u ON u.id = m.sender_id
        WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
        AND m.listing_id = ?
        ORDER BY m.created_at ASC
    ");
    $messagesStmt->execute([$current_user_id, $other_user_id, $other_user_id, $current_user_id, $chat_listing_id]);
    $chat_messages = $messagesStmt->fetchAll();
    
    // Mark messages as read
    $updateStmt = $pdo->prepare("UPDATE messages SET read_status = 'read' WHERE sender_id = ? AND receiver_id = ? AND read_status = 'unread'");
    $updateStmt->execute([$other_user_id, $current_user_id]);
    
    $selected_conversation = [
        'other_user_id' => $other_user_id,
        'other_user_name' => $other_user['full_name'] ?? 'Unknown',
        'listing_id' => $chat_listing_id,
        'listing_title' => $chat_listing['title'] ?? 'Unknown Item',
        'listing_price' => $chat_listing['price'] ?? 0
    ];
}

// Check if starting a new conversation from listing
$new_message_listing = null;
$new_message_seller = null;
if ($action === 'new' && $listing_id && $seller_id) {
    // Get listing info
    $listingStmt = $pdo->prepare("SELECT title, price, user_id FROM listings WHERE id = ?");
    $listingStmt->execute([$listing_id]);
    $new_message_listing = $listingStmt->fetch();
    
    // Get seller info
    $sellerStmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
    $sellerStmt->execute([$seller_id]);
    $seller = $sellerStmt->fetch();
    $new_message_seller = $seller['full_name'] ?? 'Unknown';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - GUMMY Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .navbar {
            display: flex;
            align-items: center;
            background-color: #097c87;
            padding: 15px 20px;
            color: white;
            flex-wrap: wrap;
            gap: 10px;
        }
        .logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-right: auto;
        }
        .logo-img {
            max-height: 60px;
            width: auto;
            display: block;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .messages-layout {
            display: flex;
            gap: 20px;
            height: 70vh;
        }
        .conversations-list {
            width: 350px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .conversations-header {
            padding: 20px;
            background: #097c87;
            color: white;
        }
        .conversations-header h2 {
            margin: 0;
        }
        .conversation-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }
        .conversation-item:hover {
            background: #f5f5f5;
        }
        .conversation-item.active {
            background: #e8f4f5;
            border-left: 3px solid #097c87;
        }
        .conversation-user {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .conversation-listing {
            font-size: 12px;
            color: #097c87;
            margin-bottom: 5px;
        }
        .conversation-preview {
            font-size: 14px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .unread-badge {
            background: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 10px;
        }
        .chat-area {
            flex: 1;
            background: white;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .chat-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header h3 {
            margin: 0;
            color: #333;
        }
        .chat-header .listing-info {
            font-size: 14px;
            color: #097c87;
        }
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.4;
        }
        .message.sent {
            align-self: flex-end;
            background: #097c87;
            color: white;
            border-bottom-right-radius: 5px;
        }
        .message.received {
            align-self: flex-start;
            background: #e9ecef;
            color: #333;
            border-bottom-left-radius: 5px;
        }
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }
        .chat-input {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }
        .chat-input input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
        }
        .chat-input input:focus {
            border-color: #097c87;
        }
        .chat-input button {
            padding: 12px 25px;
            background: #097c87;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .chat-input button:hover {
            background: #065a63;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state h2 {
            color: #097c87;
        }
        .back-link {
            display: inline-block;
            padding: 8px 15px;
            color: #097c87;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .footer {
    
            background:#097c87;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
    <style>
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
            margin-right: 0;
            font-size: inherit;
            font-weight: inherit;
        }
        .logo-img {
            max-height: 60px;
            width: auto;
            display: block;
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
              @media (max-width: 900px) {
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
                order: 0;
            }
            .messages-container {
                flex-direction: column;
                height: auto;
                gap: 0;
            }
            .conversations-list {
                width: 100%;
                border-radius: 0;
                border-bottom: 1px solid #ddd;
                max-height: 600px;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                margin-bottom: 20px;
            }
           
            .conversations-list::-webkit-scrollbar-track {
                background: transparent;
            }
            .conversations-list::-webkit-scrollbar-thumb {
                background: #097c87;
                border-radius: 4px;
            }
            .chat-panel {
                flex: 1;
                border-radius: 10px;
            }
        
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php" aria-label="GUMMY Marketplace Home">
                <img src="../images/logo.png" alt="GUMMY Marketplace" class="logo-img" />
            </a>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="casualtraders.php">Casual</a>
            <a href="informaltraders.php">Informal</a>
            <a href="mylistings.php">My Listings</a>
            <a href="messages.php">Messages</a>
            <a href="trackorder.php">Track Order</a>
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <?php if ($action === 'new' && $new_message_listing): ?>
            <a href="index.php" class="back-link">← Back to listings</a>
            <div class="chat-area">
                <div class="chat-header">
                    <h3>Chat with <?php echo htmlspecialchars($new_message_seller); ?></h3>
                    <span class="listing-info"><?php echo htmlspecialchars($new_message_listing['title']); ?> - R <?php echo number_format($new_message_listing['price'], 0, ',', ' '); ?></span>
                </div>
                <form method="POST" class="chat-messages" style="display: flex; flex-direction: column; justify-content: center;">
                    <input type="hidden" name="receiver_id" value="<?php echo $seller_id; ?>">
                    <input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>">
                    <div class="empty-state">
                        <h2>No Message yet.</h2>
                        
    
                       
                    </div>
                    <div class="chat-input">
                        <input type="text" name="message" placeholder="Type your message..." required>
                        <button type="submit" name="send_message">Send</button>
                    </div>
                </form>
            </div>
        <?php elseif ($selected_conversation): ?>
            <a href="messages.php" class="back-link">← Back to messages</a>
            <div class="chat-area">
                <div class="chat-header">
                    <h3><?php echo htmlspecialchars($selected_conversation['other_user_name']); ?></h3>
                    <span class="listing-info"><?php echo htmlspecialchars($selected_conversation['listing_title']); ?> - R <?php echo number_format($selected_conversation['listing_price'], 0, ',', ' '); ?></span>
                </div>
                <div class="chat-messages" id="chatMessages">
                    <?php if (empty($chat_messages)): ?>
                        <div class="empty-state">
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($chat_messages as $msg): ?>
                            <div class="message <?php echo $msg['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                                <?php echo htmlspecialchars($msg['message']); ?>
                                <div class="message-time"><?php echo date('M d, h:i A', strtotime($msg['created_at'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <form method="POST" class="chat-input">
                    <input type="hidden" name="receiver_id" value="<?php echo $selected_conversation['other_user_id']; ?>">
                    <input type="hidden" name="listing_id" value="<?php echo $selected_conversation['listing_id']; ?>">
                    <input type="text" name="message" placeholder="Type your message..." required>
                    <button type="submit" name="send_message">Send</button>
                </form>
            </div>
        <?php else: ?>
            <div class="messages-layout">
                <div class="conversations-list">
                    <div class="conversations-header">
                        <h2><i class="fa-solid fa-comments"></i>Messages</h2>
                    </div>
                    <?php if (empty($conversations)): ?>
                        <div class="empty-state">
                            <p>No conversations yet</p>
                            <p>Browse listings and message sellers to start chatting</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <a href="messages.php?action=chat&user_id=<?php echo $conv['other_user_id']; ?>&listing_id=<?php echo $conv['listing_id']; ?>" style="text-decoration: none; color: inherit;">
                                <div class="conversation-item">
                                    <div class="conversation-user">
                                        <?php echo htmlspecialchars($conv['other_user_name']); ?>
                                        <?php if ($conv['unread_count'] > 0): ?>
                                            <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="conversation-listing"><?php echo htmlspecialchars($conv['listing_title'] ?? 'Unknown Item'); ?></div>
                                    <div class="conversation-preview"><?php echo htmlspecialchars($conv['last_message'] ?? ''); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="chat-area">
                    <div class="empty-state" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                        <h2>Select a conversation </h2>
                        <p>Choose a conversation from the list to view messages</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; 2026 GUMMY | Trading</p>
    </div>

    <script>
        // Auto-refresh messages every 5 seconds for real-time feel
        function refreshMessages() {
            <?php if ($selected_conversation): ?>
            const currentUrl = window.location.href;
            fetch(currentUrl)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMessages = doc.getElementById('chatMessages');
                    const currentMessages = document.getElementById('chatMessages');
                    if (newMessages && currentMessages) {
                        currentMessages.innerHTML = newMessages.innerHTML;
                    }
                })
                .catch(err => console.log('Error refreshing:', err));
            <?php endif; ?>
        }

        // Refresh every 5 seconds
        setInterval(refreshMessages, 5000);
    </script>
</body>
</html>
