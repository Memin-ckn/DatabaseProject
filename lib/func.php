<?php
require "config.php";
function getCount($conn, $table, $customer = null)
{
    $whereSql = "";
    $params = [];
    if (in_array($table, ISDELETETABLE)) {
        $whereSql = "WHERE ISDELETE = 0 ";
        if ($customer && $customer !== 'memin') {
            $whereSql .= "AND CUSTOMER = ?";
            $params[] = $customer;
        } else {
            $params = [];
        }
    }

    $countSql = "SELECT COUNT(*) AS total FROM $table $whereSql";
    $stmt = sqlsrv_prepare($conn, $countSql, $params);
    if (sqlsrv_execute($stmt) === false) {
        return "Error retrieving count";
        //return null;
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['total'];
}

function getName($conn, $column, $table, $customer)
{
    $nameSql = "SELECT $column as name FROM $table WHERE CUSTOMER = '$customer'";
    $nameStmt = sqlsrv_prepare($conn, $nameSql);
    if (sqlsrv_execute($nameStmt) === false) {
        /* echo "Error retrieving name"; */
        return null;
    }
    $row = sqlsrv_fetch_array($nameStmt, SQLSRV_FETCH_ASSOC);
    return $row['name'];
}


?>