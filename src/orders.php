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

        <div class="widget form">
            <!-- Filter Form -->
            <form method="GET" action="">
                <ul>
                    <li>
                        <label for="username">CUSTOMER:</label>
                        <?php echo $_SESSION['username'] ?>
                    </li>
                    <li>
                        <label for="POTYPE">POTYPE:</label>
                        <input type="text" name="POTYPE" id="POTYPE"
                            value="<?php echo isset($_GET['POTYPE']) ? htmlspecialchars($_GET['POTYPE']) : ''; ?>">
                    </li>
                    <li>

                        <label for="PRDORDER">PRDORDER:</label>
                        <input type="text" name="PRDORDER" id="PRDORDER"
                            value="<?php echo isset($_GET['PRDORDER']) ? htmlspecialchars($_GET['PRDORDER']) : ''; ?>">
                    </li>
                    <li>

                        <label for="CLIENT">CLIENT:</label>
                        <input type="text" name="CLIENT" id="CLIENT"
                            value="<?php echo isset($_GET['CLIENT']) ? htmlspecialchars($_GET['CLIENT']) : ''; ?>">
                    </li>
                    <li>

                        <label for="COMPANY">COMPANY:</label>
                        <input type="text" name="COMPANY" id="COMPANY"
                            value="<?php echo isset($_GET['COMPANY']) ? htmlspecialchars($_GET['COMPANY']) : ''; ?>">
                    </li>
                    <li>

                        <button type="submit">Filter</button>
                        <a href="orders.php"><button type="button">Reset</button></a>
                    </li>
                    <li>
                        <!-- Export Button -->
                        <button type="submit" name="export" value="csv">Export to CSV</button>
                    </li>
                </ul>
            </form>
        </div>
        <div class="widget">
            <?php

            if (isset($_GET['export'])) {
                $exportType = $_GET['export'];
                sqlsrv_close($conn);
                header('Location: ../export/csv.php');
                exit;
            }
            // Set pagination parameters
            $limit = 50;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            include "../requirements/filter.php";

            // Get the total number of records
            $countSql = "SELECT COUNT(*) AS total FROM IASPRDORDER $whereSql";
            $countStmt = sqlsrv_query($conn, $countSql, $params);

            if ($countStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
            $total_records = $row['total'];

            // Calculate total pages
            $total_pages = ceil($total_records / $limit);

            // Fetch the data with pagination
            $dataSql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY POTYPE OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

            $dataParams = array_merge($params, [$start, $limit]);
            $dataStmt = sqlsrv_query($conn, $dataSql, $dataParams);

            if ($dataStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            ?>
            <?php
            if (isset($_GET['PRDORDER']) && $_GET['PRDORDER'] !== '') {
                $qtySql = "SELECT DELIVERED, BYPRODQTY FROM IASPRDORDER $whereSql";
                $qtyStmt = sqlsrv_query($conn, $qtySql, $params);

                if ($qtyStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                if ($row = sqlsrv_fetch_array($qtyStmt, SQLSRV_FETCH_ASSOC)) {
                    // Extract the two integer values
                    $dlv = $row['DELIVERED'];
                    $byq = $row['BYPRODQTY'];
                    $totalQty = $dlv + $byq;
                    ?>
                    <div id="quantity" class="widget">
                        <p>Total quantity:
                            <?php echo $totalQty; ?>
                        </p>
                    </div>
                    <?php
                }
            }
            ?>
            <table>
                <thead>
                    <tr>
                        <th>POTYPE</th>
                        <th>PRDORDER</th>
                        <th>CLIENT</th>
                        <th>COMPANY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['POTYPE']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['PRDORDER']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['CLIENT']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['COMPANY']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php include "pagination.php" ?>

        </div>

    </div>

    <?php sqlsrv_close($conn); ?>
</body>

</html>