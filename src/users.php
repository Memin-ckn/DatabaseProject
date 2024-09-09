<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
include "../requirements/styles_and_scripts.php";
require "../lib/func.php";
// Set pagination parameters
$limit = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get the total number of records
$total_records = getCount($conn, 'SESAUSERS');

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
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Users</title>
</head>

<body>

    <?php include "../support/sidebar.php" ?>

    <div class="main-content">
        <div class="widgets">
            <a href="user_add.php" class="widgetButton">Add User</a>
            <a href="user_edit.php" class="widgetButton">Edit User</a>
        </div>
        <div class="widget">
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
</body>

</html>