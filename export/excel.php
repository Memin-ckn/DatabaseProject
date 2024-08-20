<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// Initialize spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Define column headers
$headers = ['POTYPE', 'PRDORDER', 'CLIENT', 'COMPANY'];
$sheet->fromArray($headers, NULL, 'A1');

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

$rowNum = 2;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $sheet->fromArray($row, NULL, "A$rowNum");
    $rowNum++;
}

// Set filename and headers for XLS
$filename = 'exported_data.xls';
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

// Save the file to the output stream
$writer = new Xls($spreadsheet);
$writer->save('php://output');

sqlsrv_close($conn);
exit;
