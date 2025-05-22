<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-page.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    // Sanitize and retrieve form inputs
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate required fields
    if (!$new_username || !$new_email) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Username and email cannot be empty.'];
        header('Location: ../profile.php');
        exit;
    }

    // Check for username or email conflicts
    $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->execute([$new_username, $new_email, $user_id]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Username or email already in use by another user.'];
        header('Location: ../profile.php');
        exit;
    }

    // Update username and email
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$new_username, $new_email, $user_id]);
    $_SESSION['username'] = $new_username;

    // Handle password update if fields are filled
    if ($current_password || $new_password || $confirm_new_password) {
        // Validate password fields
        if (!$current_password || !$new_password || !$confirm_new_password) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'All password fields are required to change the password.'];
            header('Location: ../profile.php');
            exit;
        }

        if ($new_password !== $confirm_new_password) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'New passwords do not match.'];
            header('Location: ../profile.php');
            exit;
        }

        if (strlen($new_password) < 6) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'New password must be at least 6 characters long.'];
            header('Location: ../profile.php');
            exit;
        }

        // Retrieve current password hash
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password'])) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Current password is incorrect.'];
            header('Location: ../profile.php');
            exit;
        }

        // Hash and update new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password_hash, $user_id]);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile and password updated successfully.'];
        header('Location: ../profile.php');
        exit;
    }

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile updated successfully.'];
    header('Location: ../profile.php');
    exit;
} else {
    header('Location: ../profile.php');
    exit;
}
?>
