<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../requirements/styles_and_scripts.php";
    require "../requirements/connection.php";
    require "../requirements/login_check.php";
    require "../lib/func.php";

    $error = "";
    $name = getName($conn, 'NAME1', 'IASSALHEAD', $customer);

    $infoSql = "SELECT USERNAME, PASSWORD FROM SESAUSERS WHERE USERNAME = ?";
    $infoStmt = sqlsrv_prepare($conn, $infoSql, [$customer]);

    if (sqlsrv_execute($infoStmt) === false) {
        $error = "Error when getting user details";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST['password'] !== $_POST['password2']) {
            echo "<h3>Passwords don't match</h3>";
        } else {
            $password = $_POST['password'];
            if (changePassword($conn, 'SESAUSERS', $customer, $password) === false) {
                $error = "Couldn't change the password";
            } else {
                header('Location: user_details.php?success=passwordChanged');
            }
        }
    }
    ?>
    <title>User Details</title>
</head>

<body>
    <?php include "../support/sidebar.php" ?>

    <div class="main-content">

        <header>
            <h1>Welcome,
                <?php echo $customer === 'memin' ? $customer : $name; ?>
            </h1>
        </header>
        <div class="widget">
            <?php if ($error): ?>
                <div class="error">
                    <?= $error ?>
                </div>
            <?php endif; ?>
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
            <?php sqlsrv_close($conn); ?>
        </div>
    </div>
</body>

</html>