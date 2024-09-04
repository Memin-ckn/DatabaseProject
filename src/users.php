<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../requirements/styles_and_scripts.php"; ?>
    <title>Users</title>
</head>
<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
?>

<body>

    <?php include "../support/sidebar.php" ?>

    <div class="main-content">
        <div class="widgets">
            <a href="user_add.php" class="widgetButton">Add User</a>
            <a href="user_edit.php" class="widgetButton">Edit User</a>
        </div>
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
            $dataSql = "SELECT USERNAME, PASSWORD FROM SESAUSERS ORDER BY USERNAME OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

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
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['USERNAME']); ?>
                            </td>
                            <td>
                                <i class="eye fa-solid fa-eye" style="color: #9707da; cursor: pointer"
                                    onclick="showPsw(this)"></i>
                                <input disabled type="password" name="userpsw" class="userpsw"
                                    value="<?php echo htmlspecialchars($row['PASSWORD']); ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php include "../requirements/pagination.php" ?>
    </div>
    <?php sqlsrv_close($conn); ?>
    <script>
        function showPsw(element) {
            var row = element.closest('tr'); // Find the closest row
            var input = row.querySelector('.userpsw'); // Get the input in this row
            var eyeIcon = row.querySelector('.eye'); // Get the eye icon in this row

            if (input.type === "password") {
                input.type = "text";
                eyeIcon.className = "eye fa-solid fa-eye-slash";
            } else {
                input.type = "password";
                eyeIcon.className = "eye fa-solid fa-eye";
            }
        }
    </script>
</body>

</html>