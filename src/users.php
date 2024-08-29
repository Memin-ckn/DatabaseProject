<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../requirements/styles_and_scripts.php"; ?>
</head>
<?php
require "../requirements/connection.php";
require "../requirements/login_check.php";
?>

<body>

    <?php include "sidebar.php" ?>

    <div class="main-content">

        <?php sqlsrv_close($conn); ?>
    </div>
</body>

</html>