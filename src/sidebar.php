<div class="sidebar">
    <h2>Dashboard</h2>
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="orders.php">Order Details</a></li>
        <li><a href="invoice.php">Invoice Details</a></li>
        <?php
        if (isset($_SESSION['customer']) && $_SESSION['customer'] === 'memin'): ?>
            <li><a href="users.php">Users</a></li>
        <?php endif; ?>

    </ul>
    <ul>
        <li><a id="logout" href="logout.php">Logout</a></li>
    </ul>
</div>