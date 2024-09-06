<?php require_once "../lib/auth_functions.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = $_POST['customer'];
    $password = $_POST['password'];
    $opr = $_POST['opr'];

    if ($opr === 'signup') {
        $password2 = $_POST['password2'];

        if ($password !== $password2) {
            $error = "Passwords do not match.";
        } elseif (!userExistsInDB($conn, $customer)) {
            $error = "Customer not found in Customer List.";
        } elseif (userExistsInSESAUSERS($conn, $customer)) {
            $error = "User already registered.";
        } else {
            if (registerUser($conn, $customer, $password)) {
                header("Location: login.php?success=registered");
                exit();
            } else {
                $error = "Error registering user.";
            }
        }
    } elseif ($opr === 'login') {
        if (validateLogin($conn, $customer, $password)) {
            session_start();
            $_SESSION['customer'] = $customer;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid customer ID or password.";
        }
    }
}



sqlsrv_close($conn);
?>

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

        <?php if ($error): ?>
            <div class="error">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="login">
            <form action="login.php" method="post">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="text" name="customer" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="opr" value="login">Log In</button>
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
    </div>
</body>

</html>