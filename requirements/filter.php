<?php
// Initialize the WHERE clause
$whereClauses = [];
$params = [];


if (isset($_SESSION['username']) && $_SESSION['username'] !== '') {
    if ($_SESSION['username'] === 'memin') {
    } else {
        $whereClauses[] = "CUSTOMER = ?";
        $params[] = $_SESSION['username'];
    }
}
// Don't bring deleted orders
if (!isset($_GET['ISDELETE'])) {
    $whereClauses[] = "ISDELETE = ?";
    $params[] = 0;
}
if (isset($_GET['POTYPE']) && $_GET['POTYPE'] !== '') {
    $whereClauses[] = "POTYPE = ?";
    $params[] = $_GET['POTYPE'];
}

if (isset($_GET['PRDORDER']) && $_GET['PRDORDER'] !== '') {
    $whereClauses[] = "PRDORDER = ?";
    $params[] = $_GET['PRDORDER'];
}

if (isset($_GET['CLIENT']) && $_GET['CLIENT'] !== '') {
    $whereClauses[] = "CLIENT = ?";
    $params[] = $_GET['CLIENT'];
}

if (isset($_GET['COMPANY']) && $_GET['COMPANY'] !== '') {
    $whereClauses[] = "COMPANY = ?";
    $params[] = $_GET['COMPANY'];
}
if (isset($_GET['STATUS3']) && $_GET['STATUS3'] !== '') {
    $whereClauses[] = "STATUS3 = ?";
    $params[] = $_GET['STATUS3'];
}

$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

?>