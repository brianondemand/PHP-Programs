<?php
session_start();
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Please fill in all fields.'];
        header('Location: ../login-page.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Logged in successfully!'];
        header('Location: ../profile.php');
        exit;
    } else {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Invalid username or password.'];
        header('Location: ../login-page.php');
        exit;
    }
} else {
    header('Location: ../login-page.php');
    exit;
}
