<?php
require '../requirements/connection.php';

$FileName = "Exported_data.csv";
$fp = fopen('php://output', 'w');

$whereClauses = [];
$params = [];

if (isset($_SESSION['CUSTOMER']) && $_SESSION['CUSTOMER'] !== '') {
    $whereClauses[] = "CUSTOMER = ?";
    $params[] = $_SESSION['CUSTOMER'];
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

$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}


// Fetch data
$sql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY PRDORDER";
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($export = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if (!isset($headings)) {
        $headings = array_keys($export);
        fputcsv($fp, $headings, ',', '"');
    }
    fputcsv($fp, $export, ',', '"');
}
fclose($fp);

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=' . $FileName . '');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile("php://output");
?>