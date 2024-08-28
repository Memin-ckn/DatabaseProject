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
if (isset($_GET['docnum'])) {
    $docNum = $_GET['docnum'];

    // Fetch all columns for the selected DOCNUM
    $sql = "SELECT DOCTYPE, DOCNUM, NAME1, CURRENCY, TELNUM, FAXNUM, TAXNUM, GROSS, SUBTOTAL, GRANDTOTAL FROM IASSALHEAD WHERE CUSTOMER = ? AND DOCNUM = ?";
    $stmt = sqlsrv_query($conn, $sql, [$_SESSION['customer'], $docNum]);

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
        echo "No details found for DOCNUM: " . htmlspecialchars($docNum);
    }

    sqlsrv_close($conn);
}
?>