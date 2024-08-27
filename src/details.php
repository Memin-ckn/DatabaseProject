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
                        <label for="STATUS3">HEPSİ:</label>
                        <input type="radio" name="STATUS3" id="STATUS3_ALL" value="">
                        <label for="STATUS3">ONAYLI:</label>
                        <input type="radio" name="STATUS3" id="STATUS3_approved" value="1" <?php echo isset($_GET['STATUS3']) && $_GET['STATUS3'] === '1' ? 'checked' : ''; ?>>
                        <label for="STATUS3">ONAYSIZ:</label>
                        <input type="radio" name="STATUS3" id="STATUS3_unapproved" value="0" <?php echo isset($_GET['STATUS3']) && $_GET['STATUS3'] === '0' ? 'checked' : ''; ?>>
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
            // Set pagination parameters
            $limit = 50;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            include "../requirements/filter.php";

            if (isset($_GET['export'])) {
                $exportType = $_GET['export'];
                sqlsrv_close($conn);
                header("Location: ../export/csv.php");
                exit;
            }
            // Get the total number of records from filter.php
            $countSql = "SELECT COUNT(*) AS total FROM IASPRDORDER $whereSql";
            $countStmt = sqlsrv_query($conn, $countSql, $params);

            if ($countStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
            $total_records = $row['total'];

            // Calculate total pages
            $total_pages = ceil($total_records / $limit);

            // Fetch the data with pagination (offset and fetch is used for pagination)
            $dataSql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY PRDORDER OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

            $dataParams = array_merge($params, [$start, $limit]);
            $dataStmt = sqlsrv_query($conn, $dataSql, $dataParams);

            if ($dataStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            ?>
            <div class="widget">
                <p>Total Records:
                    <?php echo $total_records ?>
                </p>

                <?php
                // If filtered by PRDORDER, gets details
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

                        <p>
                            Total quantity:
                            <?php echo $totalQty, " kg sevk edildi."; ?>
                        </p>

                        <?php
                    }
                }
                ?>
            </div>
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
                        <tr class="clickable-row" data-prdorder="<?php echo htmlspecialchars($row['PRDORDER']); ?>">
                            <td>
                                <i class="fa-solid fa-plus" style="color: #65e692; cursor: pointer;"></i>
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
                        <tr class="expandable-row" style="display:none;">
                            <td colspan="4">
                                <div class="expanded-content"></div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php include "pagination.php" ?>

    </div>

    <?php sqlsrv_close($conn); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Attach the click event to the <i> element
            $('.clickable-row i').on('click', function (e) {
                e.stopPropagation(); // Prevent the event from bubbling up to the row

                var prdOrder = $(this).closest('.clickable-row').data('prdorder');
                var expandableRow = $(this).closest('.clickable-row').next('.expandable-row');
                var expandedContent = expandableRow.find('.expanded-content');

                if (expandableRow.is(':visible')) {
                    expandableRow.hide(); // Collapse if already expanded
                    $(this).removeClass('fa-minus').addClass('fa-plus'); // Change icon back to plus
                } else {
                    // Fetch and display data if not already loaded
                    if (expandedContent.is(':empty')) {
                        $.ajax({
                            url: '../requirements/fetch_order_details.php',
                            type: 'GET',
                            data: { prdorder: prdOrder },
                            success: function (response) {
                                expandedContent.html(response);
                                expandableRow.show(); // Expand the row to show content
                                $(this).removeClass('fa-plus').addClass('fa-minus'); // Change icon to minus
                            }.bind(this)
                        });
                    } else {
                        expandableRow.show(); // Just show the already loaded content
                        $(this).removeClass('fa-plus').addClass('fa-minus'); // Change icon to minus
                    }
                }
            });
        });
    </script>

</body>

</html>