<?php
require_once "stempelAppManager.php";
?>

<div class="navigation flex">
    <div class="stempel">
        <a href="/index.php" class="nav-item">StempelApp</a>
    </div>
    <div class="flex">
        <?php if (!isset($_SESSION['name'])) { ?>
            <a href="/login/login.php" class="nav-item">Login</a>
        <?php } else { ?>
            <a href="/login/logout.php" class="nav-item">Logout</a>
            <a href="/dashboard/user/entity.php?id=<?= getUserIdByName($_SESSION['name']) ?>" class="nav-item">Dashboard</a>
        <?php } ?>
    </div>
</div>
