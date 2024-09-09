<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
require "../lib/func.php";

$errorMessage = '';

// Pagination setup
$limit = 25;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

list($whereSql, $params) = filter(['DOCNUM', 'NAME1', 'CITY', 'TELNUM'], 'IASSALHEAD', null, null, $_SESSION['customer']);

// Get total records
$total_records = getCount($conn, "IASSALHEAD", $whereSql, $params);
$total_pages = ceil($total_records / $limit);

// Fetch data with pagination
$dataSql = "SELECT DOCNUM, NAME1, CITY, TELNUM FROM IASSALHEAD $whereSql ORDER BY DOCNUM OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$dataParams = array_merge($params, [$start, $limit]);
$dataStmt = sqlsrv_prepare($conn, $dataSql, $dataParams);

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
    <?php include "../requirements/styles_and_scripts.php"; ?>
    <title>Invoice Details</title>
    <script type="text/javascript">
        function error(id, msg) {
            document.getElementById(id).innerText = msg;
        }
    </script>
</head>

<body>

    <?php include "../support/sidebar.php"; ?>

    <div class="main-content">
        <div class="widget form">
            <form method="GET" action="">
                <p id="error-msg"></p>
                <ul>
                    <li>
                        <label for="customer">CUSTOMER:</label>
                        <?php echo $_SESSION['customer']; ?>
                    </li>
                    <li>
                        <label for="DOCNUM">DOCNUM:</label>
                        <input type="text" name="DOCNUM" id="DOCNUM"
                            value="<?php echo isset($_GET['DOCNUM']) ? htmlspecialchars($_GET['DOCNUM']) : ''; ?>">
                    </li>
                    <li>
                        <button type="submit">Filter</button>
                        <a href="invoice.php"><button type="button">Reset</button></a>
                    </li>
                    <li>
                        <!-- Export Button -->
                        <button type="submit" name="export" value="csv">Export to CSV</button>
                    </li>
                </ul>
            </form>
        </div>

        <div class="widget">
            <p>Total Records:
                <?php echo $total_records; ?>
            </p>
            <table>
                <thead>
                    <tr>
                        <th>DOCNUM</th>
                        <th>NAME1</th>
                        <th>CITY</th>
                        <th>TELNUM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr class="clickable-row" data-docnum="<?php echo htmlspecialchars($row['DOCNUM']); ?>">
                            <td>
                                <i class="fa-solid fa-plus" style="color: #65e692; cursor: pointer;"></i>
                                <?php echo htmlspecialchars($row['DOCNUM']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['NAME1']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['CITY']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['TELNUM']); ?>
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
        // Dropdown menu script
        $(document).ready(function () {
            $('.clickable-row i').on('click', function (e) {
                e.stopPropagation();

                var docnum = $(this).closest('.clickable-row').data('docnum');
                var expandableRow = $(this).closest('.clickable-row').next('.expandable-row');
                var expandedContent = expandableRow.find('.expanded-content');

                if (expandableRow.is(':visible')) {
                    expandableRow.hide();
                    $(this).removeClass('fa-minus').addClass('fa-plus');
                } else {
                    if (expandedContent.is(':empty')) {
                        $.ajax({
                            url: '../requirements/fetch_invoice_details.php',
                            type: 'GET',
                            data: { docnum: docnum },
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
        error('error-msg', '<?php echo $errorMessage; ?>');
    </script>

</body>

</html>