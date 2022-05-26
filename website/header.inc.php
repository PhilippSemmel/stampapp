<?php
require_once "config.php";
?>
<div class="navigation flex">
    <div class="stempel">
        <p class="stempelHeader">StempelApp</p>
    </div>
    <div class="flex">
        <?php if (!isset($_SESSION['name'])): ?>
            <a href="/login/login.php" class="nav-item">Login</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['name'])): ?>
            <a href="/login/logout.php" class="nav-item">Logout</a>
            <a href="/dashboard/index.php?id="<?php echo getUserByName($_SESSION["name"], dirname(__FILE__))["Id"] ?> get class="nav-item">Dashboard</a>
        <?php endif; ?>
    </div>
</div>
