
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require "../requirements/styles_and_scripts.php"; ?>
    <title>Edit User</title>
</head>
<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
?>

<body>
    <?php include "../support/sidebar.php"; ?>
    <div class="main-content">
        <div class="widget form">
            <form action="" method="get">
                <ul>
                    <li>
                        <label for="USERNAME">USERNAME:</label>
                        <input type="text" name="USERNAME" id="USERNAME"
                            value="<?php echo isset($_GET['USERNAME']) ? htmlspecialchars($_GET['USERNAME']) : ''; ?>">
                    </li>
                    <li>
                        <button type="submit">Filter</button>
                    </li>
                </ul>
            </form>
            <form action="users.php">
                <button type="submit">Back</button>
            </form>
        </div>

        <?php
        // Initialize the WHERE clause
        $whereClauses = [];
        $params = [];

        // Apply the USERNAME filter
        if (isset($_GET['USERNAME']) && $_GET['USERNAME'] !== '') {
            $whereClauses[] = "USERNAME = ?";
            $params[] = $_GET['USERNAME'];
        }

        // Display the table only if a filter is applied
        if (count($whereClauses) > 0) {
            $whereSql = "WHERE " . implode(" AND ", $whereClauses);
            $dataSql = "SELECT USERNAME, PASSWORD FROM SESAUSERS $whereSql";
            $dataStmt = sqlsrv_query($conn, $dataSql, $params);

            if ($dataStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Display the table
            echo "<div class='widget'>
                    <table>
                    <thead>
                        <tr>
                            <th>USERNAME</th>
                            <th>PASSWORD</th>
                        </tr>
                    </thead>
                    <tbody>";

            $hasData = false;
            while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)) {
                $hasData = true;
                echo "<tr>
                        <td>" . htmlspecialchars($row['USERNAME']) . "</td>
                        <td>" . htmlspecialchars($row['PASSWORD']) . "</td>
                      </tr>";
            }

            echo "</tbody></table></div>";

            // Display the form only if the table has data
            if ($hasData) {
                echo "<div class='widget form'>
                        <form action='' method='post'>
                            <ul>
                                <li>
                                    <label for='newPASSWORD'>New Password:</label>
                                    <input type='password' name='newPASSWORD' id='newPASSWORD' value=''>
                                </li>
                                <li>
                                    <button type='submit'>Change</button>
                                </li>
                            </ul>
                        </form>
                      </div>";
            }
        }

        // Handle the POST request to update the user information
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPASSWORD = isset($_POST['newPASSWORD']) ? $_POST['newPASSWORD'] : null;

            if ($newPASSWORD) {
                $updatePasswordSql = "UPDATE SESAUSERS SET PASSWORD = ? WHERE USERNAME = ?";
                $params = [$newPASSWORD, $_GET['USERNAME']];
                $updatePasswordStmt = sqlsrv_query($conn, $updatePasswordSql, $params);
                if ($updatePasswordStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                } else {
                    echo " Password updated successfully!\n";
                }
            }
        }
        ?>
    </div>

    <?php sqlsrv_close($conn); ?>
</body>

</html>