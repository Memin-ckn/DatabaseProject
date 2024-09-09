<?php
require '../requirements/connection.php';
require "../lib/func.php";

$FileName = "Exported_data.csv";

// Prepare output for CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $FileName . '"');
header('Pragma: no-cache');
header('Expires: 0');

$fp = fopen('php://output', 'w');

// Get filter from global
$whereSql = $_SESSION['whereSql'];
$params = $_SESSION['params'];
$table = $_SESSION['table'];

if ($table === 'IASPRDORDER'){
    $sql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY PRDORDER";
}
elseif ($table === 'IASSALHEAD'){
    $sql = "SELECT DOCNUM, NAME1, CITY, TELNUM FROM IASSALHEAD $whereSql ORDER BY DOCNUM";
}

// Fetch data from the database
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