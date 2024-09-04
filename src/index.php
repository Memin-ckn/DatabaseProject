<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
include "../requirements/styles_and_scripts.php";
require "../lib/func.php";

$customer = $_SESSION["customer"];
$name = getName($conn, 'NAME1', 'IASSALHEAD', $customer);
$totalOrders = getCount($conn, 'IASPRDORDER', $customer);
$totalInvoices = getCount($conn, 'IASSALHEAD', $customer);
$totalUsers = ($customer === 'memin') ? getCount($conn, 'SESAUSERS') : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
</head>

<body>
    <?php include "../support/sidebar.php"; ?>

    <div class="main-content">
        <header>
            <h1>Welcome,
                <?php echo $customer === 'memin' ? $customer : $name; ?>
            </h1>
        </header>

        <div class="widgets">
            <div class="widget">
                <h3>Total Orders</h3>
                <p id="total-orders">
                    <?php echo $totalOrders; ?>
                </p>
            </div>

            <div class="widget">
                <h3>Total Invoices</h3>
                <p id="total-invoices">
                    <?php echo $totalInvoices; ?>
                </p>
            </div>

            <?php if ($totalUsers !== null): ?>
                <div class="widget">
                    <h3>Total Users</h3>
                    <p id="total-users">
                        <?php echo $totalUsers; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php sqlsrv_close($conn); ?>
</body>

</html>