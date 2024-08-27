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
        <!-- Displays the username at the top -->
        <header>
            <h1>Welcome,
                <!-- There isn't a real 'username' at my table so it just uses customer id -->
                <?php echo ($_SESSION['username']); ?>
            </h1>
        </header>

        <div class="widgets">
            <div class="widget">
                <h3>Total Orders</h3>
                <p id="total-orders">
                    <?php
                    $whereSql = "WHERE ISDELETE = 0 ";
                    // Get the total number of records that aren't deleted
                    if ($_SESSION['username'] !== 'memin') {

                        $whereSql = $whereSql . "AND CUSTOMER = " . $_SESSION['username'];
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