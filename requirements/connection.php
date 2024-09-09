<?php
session_start();
$serverName = "172.16.21.238";
$connectionOptions = array(
    "Database" => "TEST603",
    "Uid" => "stajyer",
    "PWD" => "Stajyer.123"
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    echo "Conn is false";
    die(print_r(sqlsrv_errors(), true));
}
?>