<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style/login_style.css">
</head>

<body>

    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <?php
        function alert($msg)
        {
            echo "<script type='text/javascript'>alert('$msg');</script>";
        }
        if (isset($error)): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="login">
            <form action="login.php" method="post">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="text" name="customer" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="opr" value="login">Log In</button>
                <h2 id="warninglabel"></h2>
            </form>
        </div>
        <div class="signup">
            <form action="login.php" method="post">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="customer" placeholder="Customer ID" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password2" placeholder="Password Again" required>
                <button type="submit" name="opr" value="signup">Sign Up</button>

            </form>
        </div>

        <?php
        require "../requirements/connection.php";
        error_reporting(E_ERROR | E_PARSE);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $customer = $_POST['customer'];
            $password = $_POST['password'];
            $opr = $_POST['opr'];

            if ($opr === 'signup') {
                if ($_POST['password'] !== $_POST['password2']) {
                    alert('Passwords do not match');
                } else {

                    // Check if the customer exists in IASPRDORDER
                    $checkUserSql = "SELECT * FROM IASPRDORDER WHERE CUSTOMER = ?";
                    $checkUserStmt = sqlsrv_prepare($conn, $checkUserSql, [$customer]);
                    sqlsrv_execute($checkUserStmt);

                    if (sqlsrv_fetch_array($checkUserStmt, SQLSRV_FETCH_ASSOC)) {
                        // Check if the customer already exists in USERS
                        $checkUserInUsersSql = "SELECT * FROM SESAUSERS WHERE USERNAME = ?";
                        $checkUserInUsersStmt = sqlsrv_prepare($conn, $checkUserInUsersSql, [$customer]);
                        sqlsrv_execute($checkUserInUsersStmt);

                        if (sqlsrv_fetch_array($checkUserInUsersStmt, SQLSRV_FETCH_ASSOC)) {
                            alert('User already registered');
                        } else {
                            // Add username and password to USERS
                            $addUserSql = "INSERT INTO SESAUSERS (USERNAME, PASSWORD) VALUES (?, ?)";
                            $addUserStmt = sqlsrv_prepare($conn, $addUserSql, [$customer, $password]);
                            if (sqlsrv_execute($addUserStmt)) {
                                alert('Registration successful!');
                                exit();
                            } else {
                                alert('Error registering user.');
                            }
                        }
                    } else {
                        alert('Customer not found in Customer List');
                    }

                    // Close the statements
                    sqlsrv_free_stmt($checkUserStmt);
                    sqlsrv_free_stmt($checkUserInUsersStmt);
                    sqlsrv_free_stmt($addUserStmt);
                }
            } else {
                // Handle login
                $loginSql = "SELECT * FROM SESAUSERS WHERE USERNAME = ? AND PASSWORD = ?";
                $loginStmt = sqlsrv_prepare($conn, $loginSql, [$customer, $password]);
                sqlsrv_execute($loginStmt);

                if (sqlsrv_fetch_array($loginStmt, SQLSRV_FETCH_ASSOC)) {
                    // Successful login
                    $_SESSION['customer'] = $customer;
                    header("Location: index.php"); // Redirect to the main page
                    exit();
                } else {
                    alert('Invalid customer id or password');
                }

                sqlsrv_free_stmt($loginStmt);
            }
        }

        sqlsrv_close($conn);
        ?>
    </div>
</body>

</html>