<?php

/**
 * User Login Page
 * 
 * This page handles user authentication with the following functionality:
 * 1. Redirects already logged-in users to home page
 * 2. Displays login form
 * 3. Validates user credentials against database
 * 4. Creates session for authenticated users
 * 5. Handles login errors gracefully
 */

session_start();
include 'includes/connect.php';

// Redirect to home if already logged in
if (isset($_SESSION['user_id'])) {
              header("Location: home.php");
              exit();
}

$message = '';
$message_type = '';

// Check for logout message
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
              $message = 'You have been successfully logged out.';
              $message_type = 'success';
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
              // Sanitize input data
              $username = trim($_POST['username']);
              $password = $_POST['password'];

              try {
                            // Validate required fields
                            if (empty($username) || empty($password)) {
                                          throw new Exception("Please fill in all fields!");
                            }

                            // Check user credentials using named placeholders
                            // Allow login with either username or email
                            $sql = "SELECT user_id, username, password FROM users WHERE username = :username OR email = :email";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                          ':username' => $username,
                                          ':email' => $username
                            ]);


                            $user = $stmt->fetch();

                            if ($user && password_verify($password, $user['password'])) {
                                          // Login successful - create session using user_id
                                          $_SESSION['user_id'] = $user['user_id'];  // Now using custom user_id
                                          $_SESSION['username'] = $user['username'];

                                          // Regenerate session ID for security
                                          session_regenerate_id(true);

                                          header("Location: home.php");
                                          exit();
                            } else {
                                          throw new Exception("Invalid username/email or password!");
                            }
              } catch (Exception $e) {
                            $message = $e->getMessage();
                            $message_type = 'error';
              } catch (PDOException $e) {
                            error_log("Login error: " . $e->getMessage());
                            $message = "An error occurred during login. Please try again.";
                            $message_type = 'error';
              }
}
?>

<!DOCTYPE html>
<html>

<head>
              <title>Login</title>
              <style>
                            body {
                                          font-family: Arial, sans-serif;
                                          max-width: 400px;
                                          margin: 50px auto;
                                          padding: 20px;
                                          background-color: #f5f5f5;
                            }

                            .container {
                                          background-color: white;
                                          padding: 30px;
                                          border-radius: 8px;
                                          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                            }

                            h2 {
                                          text-align: center;
                                          color: #333;
                                          margin-bottom: 30px;
                            }

                            input[type="text"],
                            input[type="password"] {
                                          width: 100%;
                                          padding: 12px;
                                          margin: 8px 0;
                                          border: 1px solid #ddd;
                                          border-radius: 4px;
                                          box-sizing: border-box;
                            }

                            input[type="submit"] {
                                          width: 100%;
                                          background-color: #007bff;
                                          color: white;
                                          padding: 14px 20px;
                                          margin: 8px 0;
                                          border: none;
                                          border-radius: 4px;
                                          cursor: pointer;
                                          font-size: 16px;
                            }

                            input[type="submit"]:hover {
                                          background-color: #0056b3;
                            }

                            .message {
                                          padding: 10px;
                                          margin: 10px 0;
                                          border-radius: 4px;
                                          text-align: center;
                            }

                            .success {
                                          background-color: #d4edda;
                                          color: #155724;
                                          border: 1px solid #c3e6cb;
                            }

                            .error {
                                          background-color: #f8d7da;
                                          color: #721c24;
                                          border: 1px solid #f5c6cb;
                            }

                            .link {
                                          text-align: center;
                                          margin-top: 20px;
                            }

                            a {
                                          color: #007bff;
                                          text-decoration: none;
                            }

                            a:hover {
                                          text-decoration: underline;
                            }

                            label {
                                          font-weight: bold;
                                          color: #555;
                            }
              </style>
</head>

<body>
              <div class="container">
                            <h2>Login</h2>

                            <?php if ($message): ?>
                                          <div class="message <?php echo $message_type; ?>">
                                                        <?php echo htmlspecialchars($message); ?>
                                          </div>
                            <?php endif; ?>

                            <form method="post" action="">
                                          <p>
                                                        <label>Username or Email:</label><br>
                                                        <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                                          </p>

                                          <p>
                                                        <label>Password:</label><br>
                                                        <input type="password" name="password" required>
                                          </p>

                                          <p>
                                                        <input type="submit" value="Login">
                                          </p>
                            </form>

                            <div class="link">
                                          <a href="register.php">Don't have an account? Register here</a>
                            </div>
              </div>
</body>

</html>