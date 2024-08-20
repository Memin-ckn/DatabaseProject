<?php
require '../requirements/connection.php';

// Initialize the WHERE clause and params
$whereClauses = [];
$params = [];

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
$sql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY POTYPE";
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Set filename and headers for CSV
$filename = 'exported_data.csv';
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment;filename=\"$filename\"");

// Open the output stream
$output = fopen('php://output', 'w');
empty($output);

// Add column headers
fputcsv($output, ['POTYPE', 'PRDORDER', 'CLIENT', 'COMPANY']);

// Add data rows
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
sqlsrv_close($conn);
exit;
