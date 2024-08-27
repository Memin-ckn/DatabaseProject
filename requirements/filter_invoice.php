<?php
// Initialize the WHERE clause
// Exclude deleted orders if ISDELETE is not set in the GET parameters
$whereClauses = ["ISDELETE = ?"];
$params = [0];

// Apply the filter based on the session's customer
if (isset($_SESSION['customer']) && $_SESSION['customer'] !== '') {
    if ($_SESSION['customer'] !== 'memin') {
        $whereClauses[] = "CUSTOMER = ?";
        $params[] = $_SESSION['customer'];
    }
}

// Apply the POTYPE filter
if (isset($_GET['DOCTYPE']) && $_GET['DOCTYPE'] !== '') {
    $whereClauses[] = "DOCTYPE = ?";
    $params[] = $_GET['DOCTYPE'];
}

// Apply the PRDORDER filter
if (isset($_GET['DOCNUM']) && $_GET['DOCNUM'] !== '') {
    $whereClauses[] = "DOCNUM = ?";
    $params[] = $_GET['DOCNUM'];
}

// Apply the CLIENT filter
if (isset($_GET['NAME1']) && $_GET['NAME1'] !== '') {
    $whereClauses[] = "NAME1 = ?";
    $params[] = $_GET['NAME1'];
}

// Apply the COMPANY filter
if (isset($_GET['TELNUM']) && $_GET['TELNUM'] !== '') {
    $whereClauses[] = "TELNUM = ?";
    $params[] = $_GET['TELNUM'];
}

// Build the WHERE SQL clause
$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}
$_SESSION["whereSql"] = $whereSql;
$_SESSION["params"] = $params;
?>