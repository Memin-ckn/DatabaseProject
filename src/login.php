<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="opr" value="Login">
            <input type="submit" name="opr" value="Register">
        </form>
        <?php
        require "../requirements/connection.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $opr = $_POST['opr'];


            if ($opr === 'Register') {
                // Check if the username exists in IASPRDORDER
                $checkUserSql = "SELECT * FROM IASPRDORDER WHERE CUSTOMER = ?";
                $checkUserStmt = sqlsrv_prepare($conn, $checkUserSql, [$username]);
                sqlsrv_execute($checkUserStmt);

                if (sqlsrv_fetch_array($checkUserStmt, SQLSRV_FETCH_ASSOC)) {
                    // Check if the username already exists in USERS
                    $checkUserInUsersSql = "SELECT * FROM SESAUSERS WHERE USERNAME = ?";
                    $checkUserInUsersStmt = sqlsrv_prepare($conn, $checkUserInUsersSql, [$username]);
                    sqlsrv_execute($checkUserInUsersStmt);

                    if (sqlsrv_fetch_array($checkUserInUsersStmt, SQLSRV_FETCH_ASSOC)) {
                        echo 'User already registered';
                    } else {

                        // SQL query to get the highest ID
                        $sql = "SELECT MAX(ID) AS max_id FROM SESAUSERS";
                        $stmt = sqlsrv_query($conn, $sql);

                        if ($stmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }

                        // Fetch the result
                        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

                        $highestId = $row['max_id'];

                        // Close the statement and connection
                        sqlsrv_free_stmt($stmt);

                        // Add username and password to USERS
                        $addUserSql = "INSERT INTO SESAUSERS (ID, USERNAME, PASSWORD) VALUES (?, ?, ?)";
                        $addUserStmt = sqlsrv_prepare($conn, $addUserSql, [$highestId+1,$username, $password]);
                        if (sqlsrv_execute($addUserStmt)) {
                            echo 'Registration successful!';
                            exit();
                        } else {
                            echo 'Error registering user.';
                        }
                    }
                } else {
                    echo 'Username not found in IASPRDORDER';
                }

                // Close the statements
                sqlsrv_free_stmt($checkUserStmt);
                sqlsrv_free_stmt($checkUserInUsersStmt);
                sqlsrv_free_stmt($addUserStmt);
            } else {
                // Handle login
                $loginSql = "SELECT * FROM SESAUSERS WHERE USERNAME = ? AND PASSWORD = ?";
                $loginStmt = sqlsrv_prepare($conn, $loginSql, [$username, $password]);
                sqlsrv_execute($loginStmt);

                if (sqlsrv_fetch_array($loginStmt, SQLSRV_FETCH_ASSOC)) {
                    // Successful login
                    $_SESSION['username'] = $username;
                    header("Location: index.php"); // Redirect to the main page
                    exit();
                } else {
                    echo 'Invalid username or password';
                }

                sqlsrv_free_stmt($loginStmt);
            }
        }

        sqlsrv_close($conn);
        ?>


    </div>

</body>

</html>