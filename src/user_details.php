<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../requirements/styles_and_scripts.php";
    require "../requirements/connection.php";
    require "../requirements/login_check.php";
    ?>
    <title>User Details</title>
</head>


<body>
    <?php include "sidebar.php"?>

    <div class="main-content">

        <header>
            <h1>Welcome,
                <?php
                $customer = $_SESSION["customer"];
                $nameSql = "SELECT NAME1 FROM IASSALHEAD WHERE CUSTOMER = '$customer'";
                $nameStmt = sqlsrv_query($conn, $nameSql);
                if ($nameStmt === false || $customer === 'memin') {
                    echo ($customer);
                } else {
                    $name = sqlsrv_fetch_array($nameStmt, SQLSRV_FETCH_ASSOC);
                    echo $name['NAME1'];
                }
                ?>
            </h1>
        </header>
        <div class="widget">
            <?php
            $infoSql = "SELECT USERNAME, PASSWORD FROM SESAUSERS WHERE USERNAME = ?";
            $infoStmt = sqlsrv_query($conn, $infoSql, [$customer]);

            if ($infoStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            ?>
            <table>
                <thead>
                    <tr>
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = sqlsrv_fetch_array($infoStmt, SQLSRV_FETCH_ASSOC)): ?>
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
        <div class="widget form">
            <form method="post" action="">
                <ul>
                    <li>
                        <label for="password">PASSWORD</label>
                        <input type="password" name="password" required>
                    </li>
                    <li>
                        <label for="password2">PASSWORD AGAIN</label>
                        <input type="password" name="password2" required>
                    </li>
                    <li>
                        <button type="submit">Change Password</button>
                        <a href="user_details.php"><button type="button">Cancel</button></a>
                    </li>
                </ul>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($_POST['password'] !== $_POST['password2']) {
                    echo "<h3>Passwords don't match</h3>";
                } else {
                    $password = $_POST['password'];

                    // Add username and password to USERS
                    $chgPassSql = "UPDATE SESAUSERS SET PASSWORD = ? WHERE USERNAME = ?";
                    $params = [$password, $customer];
                    $chgPassStmt = sqlsrv_query($conn, $chgPassSql, $params);
                    if ($chgPassStmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    } else {
                        header('Location: user_details.php');
                    }
                }
                // Close the statements
                sqlsrv_free_stmt($chgPassStmt);
            }
            sqlsrv_close($conn);
            ?>
        </div>
    </div>


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