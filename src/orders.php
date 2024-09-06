<?php
// Includes
include "../requirements/styles_and_scripts.php";
require "../requirements/connection.php";
require "../requirements/login_check.php";
require "../lib/func.php";

// Main logic
$limit = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

list($whereSql, $params) = filter(['POTYPE', 'PRDORDER', 'CLIENT', 'COMPANY', 'STATUS3'], 'IASPRDORDER', null, null, $customer);
$totalRecords = getCount($conn, 'IASPRDORDER', $whereSql, $params);
$total_pages = ceil($totalRecords / $limit);

$dataSql = "SELECT POTYPE, PRDORDER, CLIENT, COMPANY FROM IASPRDORDER $whereSql ORDER BY PRDORDER OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$dataStmt = sqlsrv_prepare($conn, $dataSql, array_merge($params, [$start, $limit]));

if (sqlsrv_execute($dataStmt) === false) {
    $errorMessage = 'Error getting the table';
}

if (isset($_GET['export'])) {
    header("Location: ../export/csv.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order Details</title>
</head>

<body>
    <?php include "../support/sidebar.php"; ?>
    <div class="main-content">
        <div class="widget form">
            <!-- Filter Form -->
            <form method="GET" action="">
                <ul>
                    <li>
                        <label for="customer">CUSTOMER:</label>
                        <?php echo $_SESSION['customer'] ?>
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

        <!-- Data Table -->
        <div class="widget">
            <p>Total Records:
                <?php echo $totalRecords ?>
            </p>
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
        <?php include "../requirements/pagination.php"; ?>
    </div>

    <?php sqlsrv_close($conn); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.clickable-row i').on('click', function (e) {
                e.stopPropagation();

                var prdorder = $(this).closest('.clickable-row').data('prdorder');
                var expandableRow = $(this).closest('.clickable-row').next('.expandable-row');
                var expandedContent = expandableRow.find('.expanded-content');

                if (expandableRow.is(':visible')) {
                    expandableRow.hide();
                    $(this).removeClass('fa-minus').addClass('fa-plus');
                } else {
                    if (expandedContent.is(':empty')) {
                        $.ajax({
                            url: '../requirements/fetch_order_details.php',
                            type: 'GET',
                            data: { prdorder: prdorder },
                            success: function (response) {
                                expandedContent.html(response);
                                expandableRow.show();
                                $(this).removeClass('fa-plus').addClass('fa-minus');
                            }.bind(this)
                        });
                    } else {
                        expandableRow.show();
                        $(this).removeClass('fa-plus').addClass('fa-minus');
                    }
                }
            });
        });
    </script>

</body>

</html>