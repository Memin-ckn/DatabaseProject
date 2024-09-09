<style>
    ul {
        font-size: 1em;
        list-style-type: none;
        margin: 2px;
    }

    li {
        padding: 2px;
    }
</style>
<?php
require "connection.php";
// Gets data for dropdown menu
if (isset($_GET['prdorder'])) {
    $prdOrder = $_GET['prdorder'];

    // Fetch all columns for the selected PRDORDER
    $sql = "SELECT CLIENT, COMPANY, PLANT, POTYPE, PRDORDER, MATERIAL, QUANTITY, QUNIT, DELIVERED FROM IASPRDORDER WHERE PRDORDER = ?";
    $stmt = sqlsrv_query($conn, $sql, [$prdOrder]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<ul>";
        foreach ($row as $column => $value) {
            echo "<li><strong>" . htmlspecialchars($column) . ":</strong> " . htmlspecialchars($value) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No details found for PRDORDER: " . htmlspecialchars($prdOrder);
    }

    sqlsrv_close($conn);
}
?>