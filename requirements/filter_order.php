<?php
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
if (isset($_GET['POTYPE']) && $_GET['POTYPE'] !== '') {
    $whereClauses[] = "POTYPE = ?";
    $params[] = $_GET['POTYPE'];
}

// Apply the PRDORDER filter
if (isset($_GET['PRDORDER']) && $_GET['PRDORDER'] !== '') {
    $whereClauses[] = "PRDORDER = ?";
    $params[] = $_GET['PRDORDER'];
}

// Apply the CLIENT filter
if (isset($_GET['CLIENT']) && $_GET['CLIENT'] !== '') {
    $whereClauses[] = "CLIENT = ?";
    $params[] = $_GET['CLIENT'];
}

// Apply the COMPANY filter
if (isset($_GET['COMPANY']) && $_GET['COMPANY'] !== '') {
    $whereClauses[] = "COMPANY = ?";
    $params[] = $_GET['COMPANY'];
}

// Apply the STATUS3 filter
if (isset($_GET['STATUS3']) && $_GET['STATUS3'] !== '') {
    $whereClauses[] = "STATUS3 = ?";
    $params[] = $_GET['STATUS3'];
}

// Build the WHERE SQL clause
$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}
// Set global variables
$_SESSION["whereSql"] = $whereSql;
$_SESSION["params"] = $params;
?>