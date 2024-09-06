<?php
// auth_functions.php

require_once "../requirements/connection.php";

function alert($msg)
{
    echo "<div class='error'>$msg</div>";
}

function userExistsInDB($conn, $customer)
{
    $sql = "SELECT TOP 1 CUSTOMER FROM IASPRDORDER AS o
    FULL JOIN IASSALHEAD AS h ON o.CUSTOMER = h.CUSTOMER
    WHERE o.CUSTOMER = ? OR h.CUSTOMER = ?";
    $params = array($customer, $customer);
    $stmt = sqlsrv_query($conn, $sql, $params);
    sqlsrv_execute($stmt);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) !== null;
}

function userExistsInSESAUSERS($conn, $customer)
{
    $sql = "SELECT * FROM SESAUSERS WHERE USERNAME = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer]);
    sqlsrv_execute($stmt);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) !== null;
}

function registerUser($conn, $customer, $password)
{
    $sql = "INSERT INTO SESAUSERS (USERNAME, PASSWORD) VALUES (?, ?)";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer, $password]);
    $result = sqlsrv_execute($stmt);
    return $result;
}


function validateLogin($conn, $customer, $password)
{
    $sql = "SELECT USERNAME, PASSWORD FROM SESAUSERS WHERE USERNAME = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer]);
    sqlsrv_execute($stmt);
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Compare plain text passwords
    if ($user && $user['PASSWORD'] === $password) {
        return true;
    }
    return false;
}

