<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include "../requirements/styles_and_scripts.php"; ?>
</head>
<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
?>

<body>
    <?php include "sidebar.php" ?>

    <div class="main-content">
        <!-- Displays the customer at the top -->
        <header>
            <h1>Welcome,
                <?php
                $customer = $_SESSION["customer"];
                $nameSql = "SELECT NAME1 FROM IASSALHEAD WHERE CUSTOMER = '$customer'";
                $nameStmt = sqlsrv_query($conn, $nameSql);
                if ($nameStmt === false) {
                    echo ($_SESSION['$customer']);
                } else {
                    $name = sqlsrv_fetch_array($nameStmt, SQLSRV_FETCH_ASSOC);
                    echo $name['NAME1'];
                }
                ?>
            </h1>
        </header>

        <div class="widgets">
            <div class="widget">
                <h3>Total Orders</h3>
                <p id="total-orders">
                    <?php
                    $whereSql = "WHERE ISDELETE = 0 ";
                    // Get the total number of records that aren't deleted
                    if ($_SESSION['customer'] !== 'memin') {

                        $whereSql = $whereSql . "AND CUSTOMER = " . $_SESSION['customer'];
                    }
                    $countSql = "SELECT COUNT(*) AS total FROM IASPRDORDER $whereSql";
                    $countStmt = sqlsrv_query($conn, $countSql);

                    if ($countStmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    $row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
                    $total_records = $row['total'];
                    echo $total_records ?>
                </p>

            </div>
        </div>
    </div>

    <?php sqlsrv_close($conn); ?>
</body>

</html>