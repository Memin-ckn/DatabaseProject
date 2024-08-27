<?php
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}
?>