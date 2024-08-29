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
        <div class="widget">
            <?php
            // Set pagination parameters
            $limit = 50;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            // Get the total number of records
            $countSql = "SELECT COUNT(*) AS total FROM SESAUSERS";
            $countStmt = sqlsrv_query($conn, $countSql);

            if ($countStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
            $total_records = $row['total'];

            // Calculate total pages
            $total_pages = ceil($total_records / $limit);

            // Fetch the data with pagination (offset and fetch is used for pagination)
            $dataSql = "SELECT ID, USERNAME, PASSWORD FROM SESAUSERS WHERE ID > 2 ORDER BY ID OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

            $dataParams = [$start, $limit];
            $dataStmt = sqlsrv_query($conn, $dataSql, $dataParams);

            if ($dataStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            ?>
            <div class="widget">
                <p>Total Users:
                    <?php echo $total_records ?>
                </p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['ID']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['USERNAME']); ?>
                            </td>
                            <td>
                                <i id="eye" class="fa-solid fa-eye" style="color: #9707da; cursor: pointer"
                                    onclick="showPsw()"></i>
                                <input type="password" name="userpsw" id="userpsw"
                                    value="<?php echo htmlspecialchars($row['PASSWORD']); ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php include "pagination.php" ?>
    </div>
    <?php sqlsrv_close($conn); ?>
    <script>
        function showPsw() {
            var x = document.getElementById("userpsw");
            var y = document.getElementById("eye");
            if (x.type === "password") {
                x.type = "text";
                y.className = "fa-solid fa-eye-slash"
            } else {
                x.type = "password";
                y.className = "fa-solid fa-eye"
            }
        }
    </script>
</body>

</html>