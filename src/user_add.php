<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>
    <?php
    require "../requirements/styles_and_scripts.php";
    require "../requirements/connection.php";
    require "../requirements/login_check.php";
    ?>
</head>

<body>

    <?php require "sidebar.php" ?>

    <div class="main-content">
        <div class="widget form">

            <form action="" method="post">
                <ul>
                    <li>
                        <label for="username">USERNAME</label>
                        <input type="text" name="username" required>
                    </li>
                    <li>
                        <label for="password">PASSWORD</label>
                        <input type="password" name="password" required>
                    </li>
                    <li>
                        <label for="password2">PASSWORD AGAIN</label>
                        <input type="password" name="password2" required>
                    </li>
                    <li>
                        <button type="submit">Add User</button>
                    </li>
                </ul>
            </form>
            <form action="users.php">
                <button type="submit">Back</button>
            </form>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST['password'] !== $_POST['password2']) {
                echo "<h3>Passwords don't match</h3>";
            } else {
                $customer = $_POST['customer'];
                $password = $_POST['password'];

                // Check if the customer already exists in USERS
                $checkUserInUsersSql = "SELECT * FROM SESAUSERS WHERE USERNAME = ?";
                $checkUserInUsersStmt = sqlsrv_prepare($conn, $checkUserInUsersSql, [$customer]);
                sqlsrv_execute($checkUserInUsersStmt);

                if (sqlsrv_fetch_array($checkUserInUsersStmt, SQLSRV_FETCH_ASSOC)) {
                    echo 'User already registered';
                } else {
                    // Add username and password to USERS
                    $addUserSql = "INSERT INTO SESAUSERS (USERNAME, PASSWORD) VALUES (?, ?)";
                    $addUserStmt = sqlsrv_prepare($conn, $addUserSql, [$customer, $password]);
                    if (sqlsrv_execute($addUserStmt)) {
                        echo 'Registration successful!';
                        exit();
                    } else {
                        echo 'Error registering user.';
                    }
                }
                // Close the statements
                sqlsrv_free_stmt($checkUserInUsersStmt);
                sqlsrv_free_stmt($addUserStmt);
            }
        }
        sqlsrv_close($conn);
        ?>

    </div>

</body>

</html>