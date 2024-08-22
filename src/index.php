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
        <header>
            <h1>Welcome,
                <?php echo ($_SESSION['username']); ?>
            </h1>
        </header>



        <div class="widgets">
            <div class="widget">
                <h3>Total Orders</h3>
                <p id="total-orders">
                    <?php
                    // Get the total number of records
                    if ($_SESSION['username'] === 'memin') {
                        $whereSql = '';
                    } else {
                        $whereSql = "WHERE CUSTOMER = " . $_SESSION['username'];
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
            <div class="widget">
                <h3>Total Departments</h3>
                <p id="total-departments">
                    0
                </p>
            </div>
            <div class="widget">
                <h3> Total Roles</h3>
                <p id="total-roles">
                    0
                </p>
            </div>
        </div>

    </div>


    <?php sqlsrv_close($conn); ?>
</body>

</html>