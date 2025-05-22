<?php
// PDO (PHP Data Objects)

$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


// Supports: multiple database types (MySQL, PostgreSQL, SQLite, etc.)

// Best choice for flexibility, security (prepared statements), and modern applications.