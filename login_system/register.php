<?php

/**
 * User Registration Page
 * 
 * This page handles user account creation with the following functionality:
 * 1. Displays registration form
 * 2. Validates user input (required fields, password match)
 * 3. Checks for existing usernames/emails
 * 4. Hashes passwords securely before storage
 * 5. Inserts new user into database
 */

session_start();
include 'includes/connect.php';

$message = '';
$message_type = '';

// Process form submission when POST request is made
if ($_SERVER["REQUEST_METHOD"] == "POST") {
              // Sanitize and retrieve form data
              $username = trim($_POST['username']);
              $email = trim($_POST['email']);
              $password = $_POST['password'];
              $confirm_password = $_POST['confirm_password'];

              try {
                            // Basic validation
                            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                                          throw new Exception("All fields are required!");
                            }

                            if (strlen($password) < 6) {
                                          throw new Exception("Password must be at least 6 characters long!");
                            }

                            if ($password !== $confirm_password) {
                                          throw new Exception("Passwords do not match!");
                            }

                            // Check if username or email already exists using named placeholders
                            $check_sql = "SELECT id FROM users WHERE username = :username OR email = :email";
                            $check_stmt = $pdo->prepare($check_sql);
                            $check_stmt->execute([
                                          ':username' => $username,
                                          ':email' => $email
                            ]);

                            if ($check_stmt->rowCount() > 0) {
                                          throw new Exception("Username or email already exists!");
                            }

                            // Generate unique user ID
                            $user_id = generateUniqueUserId($pdo);

                            // Hash the password using PHP's built-in password_hash function
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            // Insert new user using named placeholders (including user_id)
                            $insert_sql = "INSERT INTO users (user_id, username, email, password) VALUES (:user_id, :username, :email, :password)";
                            $insert_stmt = $pdo->prepare($insert_sql);
                            $insert_stmt->execute([
                                          ':user_id' => $user_id,
                                          ':username' => $username,
                                          ':email' => $email,
                                          ':password' => $hashed_password
                            ]);

                            $message = "Registration successful! Your User ID is: " . $user_id . ". You can now login.";
                            $message_type = 'success';
              } catch (Exception $e) {
                            $message = $e->getMessage();
                            $message_type = 'error';
              } catch (PDOException $e) {
                            error_log("Registration error: " . $e->getMessage());
                            $message = "An error occurred during registration. Please try again.";
                            $message_type = 'error';
              }
}
?>

<!DOCTYPE html>
<html>

<head>
              <title>Register</title>
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
                            input[type="email"],
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
                                          background-color: #4CAF50;
                                          color: white;
                                          padding: 14px 20px;
                                          margin: 8px 0;
                                          border: none;
                                          border-radius: 4px;
                                          cursor: pointer;
                                          font-size: 16px;
                            }

                            input[type="submit"]:hover {
                                          background-color: #45a049;
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
                            <h2>Create Account</h2>

                            <?php if ($message): ?>
                                          <div class="message <?php echo $message_type; ?>">
                                                        <?php echo htmlspecialchars($message); ?>
                                          </div>
                            <?php endif; ?>

                            <form method="post" action="">
                                          <p>
                                                        <label>Username:</label><br>
                                                        <input type="text" name="username" required>
                                          </p>

                                          <p>
                                                        <label>Email:</label><br>
                                                        <input type="email" name="email" required>
                                          </p>

                                          <p>
                                                        <label>Password:</label><br>
                                                        <input type="password" name="password" required>
                                          </p>

                                          <p>
                                                        <label>Confirm Password:</label><br>
                                                        <input type="password" name="confirm_password" required>
                                          </p>

                                          <p>
                                                        <input type="submit" value="Register">
                                          </p>
                            </form>

                            <div class="link">
                                          <a href="login.php">Already have an account? Login here</a>
                            </div>
              </div>
</body>

</html>