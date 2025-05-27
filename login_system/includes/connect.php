<?php

/**
 * Database Configuration File
 * 
 * This file establishes a connection to the MySQL database using PDO.
 * PDO (PHP Data Objects) provides a secure way to interact with databases
 * and supports named placeholders for prepared statements.
 */

// Database configuration variables
$dbHost = 'localhost';
$dbName = 'login_system';
$dbUser = 'root';
$dbPass = 'dbadmin';

/**
 * Generate a random user ID
 * Format: USR + 8 random characters (uppercase letters and numbers)
 */
function generateUserId()
{
              $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
              $randomString = '';
              for ($i = 0; $i < 8; $i++) {
                            $randomString .= $characters[rand(0, strlen($characters) - 1)];
              }
              return 'USR' . $randomString;
}

/**
 * Check if user ID already exists in database
 */
function isUserIdUnique($pdo, $user_id)
{
              try {
                            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
                            $stmt->execute([':user_id' => $user_id]);
                            return $stmt->rowCount() === 0;
              } catch (PDOException $e) {
                            error_log("Error checking user ID uniqueness: " . $e->getMessage());
                            return false;
              }
}

/**
 * Generate a unique user ID
 */
function generateUniqueUserId($pdo)
{
              $maxAttempts = 10;
              $attempts = 0;

              do {
                            $user_id = generateUserId();
                            $attempts++;

                            if ($attempts >= $maxAttempts) {
                                          throw new Exception("Unable to generate unique user ID after $maxAttempts attempts");
                            }
              } while (!isUserIdUnique($pdo, $user_id));

              return $user_id;
}

try {
              // Create PDO connection with error handling
              $connectionString = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

              $pdo = new PDO(
                            $connectionString,
                            $dbUser,
                            $dbPass,
                            [
                                          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                          PDO::ATTR_EMULATE_PREPARES => false
                            ]
              );
} catch (PDOException $e) {
              error_log("Database connection failed: " . $e->getMessage());
              die("Database connection failed. Please try again later.");
}
