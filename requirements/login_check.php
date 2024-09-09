<?php
// Check if global customer set
if (!isset($_SESSION['customer'])) {
    // if not send to login
    header("Location: ../src/login.php");
    exit();
}

// Set global var customer
$customer = $_SESSION['customer'];

// Get the name of the current file
$currentFile = basename($_SERVER['PHP_SELF']);
$restrictedFiles = ['users.php', 'user_edit.php', 'user_add.php'];

if (in_array($currentFile, $restrictedFiles) && $_SESSION['customer'] !== 'memin') {
    // Redirect non-admin users trying to access restricted files
    header('Location: ../src/index.php');
    exit;
}
?>