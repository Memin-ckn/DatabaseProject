<?php
require '../requirements/connection.php';

$FileName = "Exported_data.csv";

// Prepare output for CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $FileName . '"');
header('Pragma: no-cache');
header('Expires: 0');

$fp = fopen('php://output', 'w');

$whereSql = $_SESSION['whereSql'];
$params = $_SESSION['params'];

// Fetch data from the database
$sql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY PRDORDER";
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Write CSV headers
$headings = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if (!$headings) {
        // Output column names as the first row
        fputcsv($fp, array_keys($row));
        $headings = true;
    }
    // Output each row
    fputcsv($fp, $row);
}

// Close the file pointer
fclose($fp);
sqlsrv_close($conn);
exit();
?>