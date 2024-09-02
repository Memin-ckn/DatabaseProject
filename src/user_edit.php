<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?php
    require "../requirements/connection.php";
    require "../requirements/login_check.php";
    require "../requirements/styles_and_scripts.php";
    ?>
</head>

<body>
    <div class="main-content" style="margin-left: 0; width: 100%; overflow-y: hidden;">
        <div class="widget form">
            <form action="" method="get">
                <ul>
                    <li>
                        <label for="ID">ID:</label>
                        <input type="text" name="ID" id="ID"
                            value="<?php echo isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : ''; ?>">
                    </li>
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
        </div>
        <?php
        // Initialize the WHERE clause
        $whereClauses = [];
        $params = [];

        // Apply the ID filter
        if (isset($_GET['ID']) && $_GET['ID'] !== '') {
            $whereClauses[] = "ID = ?";
            $params[] = $_GET['ID'];
        }

        // Apply the USERNAME filter
        if (isset($_GET['USERNAME']) && $_GET['USERNAME'] !== '') {
            $whereClauses[] = "USERNAME = ?";
            $params[] = $_GET['USERNAME'];
        }

        // Build the WHERE SQL clause
        $whereSql = "";
        if (count($whereClauses) > 0) {
            $whereSql = "WHERE " . implode(" AND ", $whereClauses);
        }

        // Fetch the data with pagination (offset and fetch is used for pagination)
        $dataSql = "SELECT ID, USERNAME, PASSWORD FROM SESAUSERS $whereSql";
        $dataStmt = sqlsrv_query($conn, $dataSql, $params);

        if ($dataStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else if (count($whereClauses) > 0) {
            echo
                "<div class='widget'>
                    <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                    </tr>
                </thead>
                <tbody>";
            while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                        <?php echo htmlspecialchars($row['ID']); ?>
                        </td>
                        <td>
                        <?php echo htmlspecialchars($row['USERNAME']); ?>
                        </td>
                        <td>
                        <?php echo htmlspecialchars($row['PASSWORD']); ?>
                        </td>
                    </tr>
                    </tbody>
                    </table>
            <?php endwhile; ?>
                <div class="widget form">
                    <form action="" method="post">
                        <ul>
                            <li>
                                <label for="newID">New ID:</label>
                                <input type="text" name="newID" id="newID" value="">
                            </li>
                            <li>
                                <label for="newPASSWORD">New Password:</label>
                                <input type="password" name="newPASSWORD" id="newPASSWORD" value="">
                            </li>
                            <li>
                                <button type="submit">Change</button>
                            </li>
                        </ul>
                    </form>
                </div>
            <?php
        }
        $params = [];
        // Handle the POST request to update the user information
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newID = isset($_POST['newID']) ? $_POST['newID'] : null;
            $newPASSWORD = isset($_POST['newPASSWORD']) ? $_POST['newPASSWORD'] : null;

            if ($newPASSWORD) {
                // Update the password in the table
                $updatePasswordSql = "UPDATE SESAUSERS SET PASSWORD = ? WHERE ID = ?";
                $params[] = $newPASSWORD;
                $params[] = $_GET['ID'];
                $updatePasswordStmt = sqlsrv_query($conn, $updatePasswordSql, $params);
                if ($updatePasswordStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                } else {
                    echo "Password updated successfully!";
                }
            }
            $params = [];
            if ($newID) {
                // Check if the new ID already exists in the table
                $checkIDSql = "SELECT ID FROM SESAUSERS WHERE ID = ?";
                $checkIDStmt = sqlsrv_query($conn, $checkIDSql, [$newID]);
                if (sqlsrv_fetch_array($checkIDStmt, SQLSRV_FETCH_ASSOC)) {
                    echo "The new ID already exists. Please choose a different ID.";
                } else {
                    // Update the ID in the table
                    $updateIDSql = "UPDATE SESAUSERS SET ID = ? WHERE ID = ?";
                    $params[] = $newID;
                    $params[] = $_GET['ID'];
                    $updateIDStmt = sqlsrv_query($conn, $updateIDSql, $params);
                    if ($updateIDStmt === false) {
                        echo "$newID, $newPASSWORD";
                        die(print_r(sqlsrv_errors(), true));
                    } else {
                        echo "ID updated successfully!";
                    }
                }
            }

        }
        ?>

    </div>

    </div>
    <?php sqlsrv_close($conn); ?>
    </div>

</body>

</html>