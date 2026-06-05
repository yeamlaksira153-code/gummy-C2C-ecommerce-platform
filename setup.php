<?php
// Database setup script - run this once to create database and tables

$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'yamla1819';

try {
    // Connect without database first
    $pdo = new PDO("mysql:host=$db_host;port=3309", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS gummy");
    echo "Database 'gummy' created successfully!<br>";
    
    // Use the database
    $pdo->exec("USE gummy");
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(50),
        id_number VARCHAR(50),
        id_verified TINYINT(1) DEFAULT 0,
        seller_type ENUM('casual', 'informal') DEFAULT 'casual',
        id_document VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "Users table created successfully!<br>";
    
    // Create listings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS listings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(100),
        `condition` VARCHAR(50),
        seller_type ENUM('casual', 'informal') NOT NULL,
        location VARCHAR(255),
        negotiable ENUM('yes', 'no') DEFAULT 'no',
        trader_name VARCHAR(255),
        id_number VARCHAR(50),
        years_experience INT,
        delivery_options VARCHAR(50),
        warranty VARCHAR(50),
        status ENUM('active', 'sold', 'removed') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Listings table created successfully!<br>";
    
    // Create messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        listing_id INT NOT NULL,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        read_status ENUM('unread', 'read') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Messages table created successfully!<br>";
    
    // Create listing_images table
    $pdo->exec("CREATE TABLE IF NOT EXISTS listing_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        listing_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
    )");
    echo "Listing images table created successfully!<br>";

    $pdo->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        description VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Roles table created successfully!<br>";

    $pdo->exec("CREATE TABLE IF NOT EXISTS user_roles (
        user_id INT NOT NULL,
        role_id INT NOT NULL,
        PRIMARY KEY (user_id, role_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    )");
    echo "User roles table created successfully!<br>";

    $pdo->exec("INSERT IGNORE INTO roles (name, description) VALUES
        ('admin', 'Administrator with full access'),
        ('manager', 'Manager with limited admin access'),
        ('user', 'Regular user')");
    echo "Default roles seeded successfully!<br>";
    
    echo "<br>All tables created successfully! You can now use the website.";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
