<?php
require "config.php";
function getCount($conn, $table, $whereSql = null, $params = null, $customer = null)
{

    if ($customer !== null) {
        if (in_array($table, ISDELETETABLE)) {
            $whereSql .= "WHERE ISDELETE = 0 ";
            if ($customer && !in_array($customer, admin)) {
                $whereSql .= "AND CUSTOMER = ?";
                $params[] = $customer;
            } else {
                $params = [];
            }
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

function filter(array $columns, $table, $whereClauses = null, array $params = null, $customer = null)
{
    if ($whereClauses !== null && $params !== null) {
        $whereSql = $whereClauses ? "WHERE " . implode(" AND ", $whereClauses) : "";
        if ($customer !== null) {
            if ($customer && !in_array($customer, admin)) {
                $whereSql .= "AND CUSTOMER = ?";
                $params[] = $customer;
            } else {
                $params = [];
            }
        }
    } else {
        $whereClauses = [];
        $params = [];
        foreach ($columns as $filter) {
            if (isset($_GET[$filter]) && $_GET[$filter] !== '') {
                $whereClauses[] = "$filter = ?";
                $params[] = $_GET[$filter];
            }
        }
        if (in_array($table, ISDELETETABLE)) {
            $whereClauses[] = "ISDELETE = ?";
            $params[] = 0;
        }
        if ($customer !== null) {
            if (!in_array($customer, admin)) {
                $whereClauses[] = "CUSTOMER = ?";
                $params[] = $customer;
            }
        }
        $whereSql = $whereClauses ? "WHERE " . implode(" AND ", $whereClauses) : "";
    }

    $_SESSION['whereSql'] = $whereSql;
    $_SESSION['params'] = $params;
    $_SESSION['table'] = $table;
    return array($whereSql, $params);
}


?>