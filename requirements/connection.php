<?php
session_start();
$serverName = "SERVER IP";
$connectionOptions = array(
    "Database" => "DATABASE NAME",
    "Uid" => "USER ID",
    "PWD" => "PASSWORD"
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    echo "Conn is false";
    die(print_r(sqlsrv_errors(), true));
}
?>