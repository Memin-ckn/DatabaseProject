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
// Gets data for dropdown menu
require "connection.php";
if (isset($_GET['docnum'])) {
    $whereClauses = ["ISDELETE = ?"];
    $params = [0];
    if (isset($_SESSION['customer']) && $_SESSION['customer'] !== '') {
        if ($_SESSION['customer'] !== 'memin') {
            $whereClauses[] = "CUSTOMER = ?";
            $params[] = $_SESSION['customer'];
        }
    }
    if (isset($_GET['DOCNUM']) && $_GET['DOCNUM'] !== '') {
        $whereClauses[] = "DOCNUM = ?";
        $params[] = $_GET['DOCNUM'];
    }
    $whereSql = "";
    if (count($whereClauses) > 0) {
        $whereSql = "WHERE " . implode(" AND ", $whereClauses);
    }

    // Fetch all columns for the selected DOCNUM
    $sql = "SELECT DOCTYPE, DOCNUM, NAME1, CURRENCY, TELNUM, FAXNUM, TAXNUM, GROSS, SUBTOTAL, GRANDTOTAL FROM IASSALHEAD $whereSql";
    $stmt = sqlsrv_query($conn, $sql, $params);

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
        echo $_SESSION['customer'];
        echo "No details found for DOCNUM: " . htmlspecialchars($docNum);
    }

    sqlsrv_close($conn);
}
?>