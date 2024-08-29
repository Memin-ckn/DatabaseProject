<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- <link rel="stylesheet" href="../style/login_style.css"> -->
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
    </div>

</body>

</html>