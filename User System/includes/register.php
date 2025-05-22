<?php
session_start();
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!$username || !$email || !$password || !$confirm_password) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Please fill in all fields.'];
        header('Location: ../register-page.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Passwords do not match.'];
        header('Location: ../register-page.php');
        exit;
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Username or email already taken.'];
        header('Location: ../register-page.php');
        exit;
    }

    // Hash password and insert user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password_hash]);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Account created successfully! Please login.'];
    header('Location: ../login-page.php');
    exit;
} else {
    header('Location: ../register-page.php');
    exit;
}
