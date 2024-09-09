<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
require "../requirements/styles_and_scripts.php";
require "../lib/func.php";

$error = "";
$hasData = false;

// Handle GET request and retrieve user data
if (isset($_GET['USERNAME']) && $_GET['USERNAME'] !== '') {
    list($whereSql, $params) = filter(['USERNAME'], 'SESAUSERS', null, null, $_GET['USERNAME']);
    $userData = fetchUserData($conn, $whereSql, $params);
    if ($userData === false) {
        $error = "Error fetching the user details";
    } else {
        $hasData = !empty($userData);
    }
}

// Handle POST request for updating the password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['newPASSWORD']) && $_POST['newPASSWORD'] !== '') {
        $newPASSWORD = $_POST['newPASSWORD'];
        $username = $_GET['USERNAME'] ?? null;

        if ($username && changePassword($conn, 'SESAUSERS', $username, $newPASSWORD)) {
            header('Location: user_edit.php?USERNAME=memin');
        } else {
            $error = "Failed to update the password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>

<body>
    <?php include "../support/sidebar.php"; ?>
    <div class="main-content">
        <div class="widget form">
            <!-- Filter Form -->
            <form action="" method="get">
                <ul>
                    <li>
                        <label for="USERNAME">USERNAME:</label>
                        <input type="text" name="USERNAME" id="USERNAME"
                            value="<?= htmlspecialchars($_GET['USERNAME'] ?? '') ?>">
                    </li>
                    <li>
                        <button type="submit">Filter</button>
                    </li>
                </ul>
            </form>

            <!-- Back Button -->
            <form action="users.php">
                <button type="submit">Back</button>
            </form>

            <!-- Error Display -->
            <?php if ($error): ?>
                <div class="error">
                    <?= $error ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Data Table -->
        <?php if ($hasData): ?>
            <div class='widget'>
                <table>
                    <thead>
                        <tr>
                            <th>USERNAME</th>
                            <th>PASSWORD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userData as $row): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($row['USERNAME']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['PASSWORD']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Password Change Form -->
            <div class='widget form'>
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
            </div>
        <?php endif; ?>
    </div>

    <?php sqlsrv_close($conn); ?>
</body>

</html>