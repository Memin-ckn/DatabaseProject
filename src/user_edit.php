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
            $oldID = $_GET['ID'];
            $whereClauses[] = "ID = ?";
            $params[] = $oldID;
        }

        // Apply the USERNAME filter
        if (isset($_GET['USERNAME']) && $_GET['USERNAME'] !== '') {
            $whereClauses[] = "USERNAME = ?";
            $params[] = $_GET['USERNAME'];
            if (!isset($oldID)) {
                $oldIDSql = "SELECT ID FROM SESAUSERS WHERE USERNAME = ?";
                $oldIDStmt = sqlsrv_query($conn, $oldIDSql, [$_GET['USERNAME']]);
                $row = sqlsrv_fetch_array($oldIDStmt, SQLSRV_FETCH_ASSOC);
                $oldID = $row['ID'];
            }
        }

        // Build the WHERE SQL clause
        $whereSql = "";
        if (count($whereClauses) > 0) {
            $whereSql = "WHERE " . implode(" AND ", $whereClauses);
        }

        $dataSql = "SELECT ID, USERNAME, PASSWORD FROM SESAUSERS $whereSql";
        $dataStmt = sqlsrv_query($conn, $dataSql, $params);

        if ($dataStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            echo "<div class='widget'>
                    <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USERNAME</th>
                            <th>PASSWORD</th>
                        </tr>
                    </thead>
                    <tbody>";
            $hasData = false;
            while ($row = sqlsrv_fetch_array($dataStmt, SQLSRV_FETCH_ASSOC)) {
                $hasData = true;
                echo "<tr>
                        <td>" . htmlspecialchars($row['ID']) . "</td>
                        <td>" . htmlspecialchars($row['USERNAME']) . "</td>
                        <td>" . htmlspecialchars($row['PASSWORD']) . "</td>
                      </tr>";
            }
            echo "</tbody></table></div>";

            // Display the form only if there's data
            if ($hasData) {
                echo "<div class='widget form'>
                        <form action='' method='post'>
                            <ul>
                                <li>
                                    <label for='newID'>New ID:</label>
                                    <input type='text' name='newID' id='newID' value=''>
                                </li>
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
            $newID = isset($_POST['newID']) ? $_POST['newID'] : null;
            $newPASSWORD = isset($_POST['newPASSWORD']) ? $_POST['newPASSWORD'] : null;

            if ($newPASSWORD) {
                // Update the password in the table
                $updatePasswordSql = "UPDATE SESAUSERS SET PASSWORD = ? WHERE ID = ?";
                $params = [$newPASSWORD, $oldID];
                $updatePasswordStmt = sqlsrv_query($conn, $updatePasswordSql, $params);
                if ($updatePasswordStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                } else {
                    echo " Password updated successfully!\n";
                }
            }

            if ($newID) {
                // Check if the new ID already exists in the table
                $checkIDSql = "SELECT ID FROM SESAUSERS WHERE ID = ?";
                $checkIDStmt = sqlsrv_query($conn,$checkIDSql, [$newID]);
                if (sqlsrv_fetch_array($checkIDStmt, SQLSRV_FETCH_ASSOC)) {
                    echo "The new ID already exists. Please choose a different ID.";
                } else {
                    // Update the ID in the table
                    $updateIDSql = "UPDATE SESAUSERS SET ID = ? WHERE ID = ?";
                    $params = [$newID, $oldID];
                    $updateIDStmt = sqlsrv_query($conn, $updateIDSql, $params);
                    if ($updateIDStmt === false) {
                        echo "$newID, $newPASSWORD";
                        die(print_r(sqlsrv_errors(), true));
                    } else {
                        echo " ID updated successfully!\n";
                    }
                }
            }
        }
        ?>
    </div>

    <?php sqlsrv_close($conn); ?>

</body>

</html>