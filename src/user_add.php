<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <?php
    require "../requirements/styles_and_scripts.php";
    require "../requirements/connection.php";
    require "../requirements/login_check.php";
    require "../lib/auth_functions.php";

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        if ($password !== $password2) {
            $error = "Passwords do not match.";
        } elseif (userExistsInSESAUSERS($conn, $customer)) {
            $error = "User already registered.";
        } else {
            if (registerUser($conn, $customer, $password)) {
                header("Location: users.php?success=registered");
                exit();
            } else {
                $error = "Error registering user.";
            }
        }
    }
    ?>
</head>

<body>
    <?php require "../support/sidebar.php" ?>

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
            <?php if ($error): ?>
                <div class="error">
                    <?= $error ?>
                </div>
            <?php endif; ?>
        </div>
        <?php sqlsrv_close($conn); ?>
    </div>
</body>

</html>