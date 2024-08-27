<!DOCTYPE html>
<html lang="en">

<head>
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
                        <label for="customer">CUSTOMER:</label>
                        <?php echo $_SESSION['customer'] ?>
                    </li>
                    <li>
                        <label for="POTYPE">POTYPE:</label>
                        <input type="text" name="POTYPE" id="POTYPE"
                            value="<?php echo isset($_GET['POTYPE']) ? htmlspecialchars($_GET['POTYPE']) : ''; ?>">
                    </li>
                    
                    <li>

                        <button type="submit">Filter</button>
                        <a href="orders.php"><button type="button">Reset</button></a>
                    </li>
                </ul>
            </form>
        </div>
        <div class="widget">
            <?php
            // Set pagination parameters
            $limit = 25;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            include "../requirements/filter_invoice.php";

            // Get the total number of records from filter.php
            $countSql = "SELECT COUNT(*) AS total FROM IASSALHEAD $whereSql";
            $countStmt = sqlsrv_query($conn, $countSql, $params);

            if ($countStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
            $total_records = $row['total'];

            // Calculate total pages
            $total_pages = ceil($total_records / $limit);
    
            // Fetch the data with pagination (offset and fetch is used for pagination)
            $dataSql = "SELECT DOCTYPE, DOCNUM, NAME1, TELNUM FROM IASSALHEAD $whereSql ORDER BY DOCNUM OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

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
            </div>
            <table>
                <thead>
                    <tr>
                        <th>DOCTYPE</th>
                        <th>DOCNUM</th>
                        <th>NAME1</th>
                        <th>TELNUM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr class="clickable-row" data-docnum="<?php echo htmlspecialchars($row['DOCNUM']); ?>">
                            <td>
                                <i class="fa-solid fa-plus" style="color: #65e692; cursor: pointer;"></i>
                                <?php echo htmlspecialchars($row['DOCTYPE']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['DOCNUM']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['NAME1']); ?>
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
                            url: '../requirements/fetch_detail_details.php',
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