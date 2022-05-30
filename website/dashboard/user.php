<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION['name'])

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<div id="content">
    <?php
    if ($user['role'] == ADMIN) {
        foreach (getUsers() as $value) {
            ?>
            <h1><?= $value["Name"] ?></h1>
            <p><?= $value["Role"] ?></p>
            <?php
        }
    } else if ($user['role'] == LEHRER) {
        foreach (getUsersByRank(0) as $value) {
            ?>
            <h1><?= $value["Name"] ?></h1>
            <p><?= $value["Role"] ?></p>
            <?php
        }
    }
    ?>
</div>
</body>
</html>
