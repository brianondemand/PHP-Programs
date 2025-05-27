<?php

/**
 * Home Page (Protected Area)
 * 
 * This page serves as the main dashboard for logged-in users:
 * 1. Checks if user is authenticated (has valid session)
 * 2. Redirects unauthenticated users to login page
 * 3. Displays welcome message with username
 * 4. Provides logout functionality
 * 5. Shows user account information
 */

session_start();

// Security check: ensure user is logged in
if (!isset($_SESSION['user_id'])) {
              // Redirect to login page if not authenticated
              header("Location: login.php");
              exit();
}

// Optional: Get additional user info from database
include 'includes/connect.php';

$user_info = null;
try {
              // Fetch user information using named placeholder (now using user_id)
              $sql = "SELECT user_id, username, email, created_at FROM users WHERE user_id = :user_id";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([':user_id' => $_SESSION['user_id']]);
              $user_info = $stmt->fetch();
} catch (PDOException $e) {
              error_log("Error fetching user info: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
              <title>Home - Dashboard</title>
              <style>
                            body {
                                          font-family: Arial, sans-serif;
                                          max-width: 800px;
                                          margin: 0 auto;
                                          padding: 20px;
                                          background-color: #f8f9fa;
                            }

                            .header {
                                          background-color: #007bff;
                                          color: white;
                                          padding: 20px;
                                          border-radius: 8px;
                                          margin-bottom: 30px;
                                          text-align: center;
                            }

                            .welcome-card {
                                          background-color: white;
                                          padding: 30px;
                                          border-radius: 8px;
                                          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                                          margin-bottom: 20px;
                            }

                            .user-info {
                                          background-color: #e9ecef;
                                          padding: 20px;
                                          border-radius: 6px;
                                          margin: 20px 0;
                            }

                            .info-item {
                                          margin: 10px 0;
                                          padding: 8px 0;
                                          border-bottom: 1px solid #ddd;
                            }

                            .info-label {
                                          font-weight: bold;
                                          color: #495057;
                                          display: inline-block;
                                          width: 120px;
                            }

                            .logout-btn {
                                          background-color: #dc3545;
                                          color: white;
                                          padding: 12px 24px;
                                          text-decoration: none;
                                          border-radius: 4px;
                                          display: inline-block;
                                          margin-top: 20px;
                                          transition: background-color 0.3s;
                            }

                            .logout-btn:hover {
                                          background-color: #c82333;
                                          text-decoration: none;
                            }

                            .success-message {
                                          background-color: #d4edda;
                                          color: #155724;
                                          padding: 15px;
                                          border-radius: 4px;
                                          border: 1px solid #c3e6cb;
                                          margin-bottom: 20px;
                            }

                            h1 {
                                          margin: 0;
                                          font-size: 2.5em;
                            }

                            h2 {
                                          color: #343a40;
                                          border-bottom: 2px solid #007bff;
                                          padding-bottom: 10px;
                            }
              </style>
</head>

<body>
              <div class="header">
                            <h1>Welcome to Your Dashboard</h1>
                            <p>You are successfully logged in!</p>
              </div>

              <div class="welcome-card">
                            <div class="success-message">
                                          <strong>Login Successful!</strong> Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                            </div>

                            <h2>Your Account Information</h2>

                            <?php if ($user_info): ?>
                                          <div class="user-info">
                                                        <div class="info-item">
                                                                      <span class="info-label">User ID:</span>
                                                                      <?php echo htmlspecialchars($user_info['user_id']); ?>
                                                        </div>
                                                        <div class="info-item">
                                                                      <span class="info-label">Username:</span>
                                                                      <?php echo htmlspecialchars($user_info['username']); ?>
                                                        </div>
                                                        <div class="info-item">
                                                                      <span class="info-label">Email:</span>
                                                                      <?php echo htmlspecialchars($user_info['email']); ?>
                                                        </div>
                                                        <div class="info-item">
                                                                      <span class="info-label">Member Since:</span>
                                                                      <?php echo date('F j, Y', strtotime($user_info['created_at'])); ?>
                                                        </div>
                                                        <div class="info-item">
                                                                      <span class="info-label">Session ID:</span>
                                                                      <?php echo htmlspecialchars(session_id()); ?>
                                                        </div>
                                          </div>
                            <?php else: ?>
                                          <p>Unable to load user information at this time.</p>
                            <?php endif; ?>

                            <h2>Available Actions</h2>
                            <p>This is your protected home page. Only authenticated users can access this area.</p>
                            <p>You can add more features here such as:</p>
                            <ul>
                                          <li>User profile management</li>
                                          <li>Settings and preferences</li>
                                          <li>Application-specific content</li>
                                          <li>Data management tools</li>
                            </ul>

                            <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">
                                          Logout
                            </a>
              </div>
</body>

</html>