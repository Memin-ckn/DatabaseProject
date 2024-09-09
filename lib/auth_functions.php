<?php
require_once "../requirements/connection.php";

// Check both our tables for user
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

// Check USERS table for user
function userExistsInSESAUSERS($conn, $customer)
{
    $sql = "SELECT * FROM SESAUSERS WHERE USERNAME = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer]);
    sqlsrv_execute($stmt);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) !== null;
}

// Add user to USERS table
function registerUser($conn, $customer, $password)
{
    $sql = "INSERT INTO SESAUSERS (USERNAME, PASSWORD) VALUES (?, ?)";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer, $password]);
    $result = sqlsrv_execute($stmt);
    return $result;
}

// Validate creditentials
function validateLogin($conn, $customer, $password)
{
    $sql = "SELECT USERNAME, PASSWORD FROM SESAUSERS WHERE USERNAME = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [$customer]);
    sqlsrv_execute($stmt);
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && $user['PASSWORD'] === $password) {
        return true;
    }
    return false;
}

