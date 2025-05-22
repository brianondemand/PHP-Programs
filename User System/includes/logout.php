<?php
session_start();
session_destroy();
session_start();
$_SESSION['toast'] = ['type' => 'success', 'message' => 'Logged out successfully.'];
header('Location: ../login-page.php');
exit;
