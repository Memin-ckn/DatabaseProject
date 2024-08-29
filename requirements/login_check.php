<?php
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

// Get the name of the current file
$currentFile = basename($_SERVER['PHP_SELF']);
$restrictedFiles = ['users.php'];

if (in_array($currentFile, $restrictedFiles) && $_SESSION['customer'] !== 'memin') {
    // Redirect non-admin users trying to access restricted files
    header('Location: ../src/index.php');
    exit;
}

?>