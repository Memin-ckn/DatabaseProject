<div class="sidebar">
    <h2>SESA Panel</h2>
    <ul>
        <li><a href="../src/index.php">Home</a></li>
        <li><a href="../src/orders.php">Order Details</a></li>
        <li><a href="../src/invoice.php">Invoice Details</a></li>
        <?php
        if (isset($_SESSION['customer']) && $_SESSION['customer'] === 'memin'): ?>
            <li><a href="../src/users.php">Users</a></li>
        <?php endif; ?>

        <li><a href="../src/user_details.php">User Details</a></li>


    </ul>
    <ul>
        <li><a id="logout" href="../support/logout.php">Logout</a></li>
    </ul>
</div>