<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-page.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    session_destroy();

    session_start();
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Account deleted successfully.'];
    header('Location: ../register-page.php');
    exit;
} else {
    header('Location: ../profile.php');
    exit;
}
