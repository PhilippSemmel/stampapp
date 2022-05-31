<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION["name"]);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<div id="content">
    <p><b>Id</b>: <?= $user['Id'] ?></p>
    <p><b>Name</b>: <?= $user['Name'] ?></p>
    <p><b>Rolle</b>: <?= ROLLEN_NAMEN[$user['Rolle']] ?></p>
    <?php if ($user['Rolle'] == LEHRER) { ?>
        <p><b>Freigeschaltet</b>: <?= getUnlockedText($user) ?></p>
    <?php } ?>
</div>
</body>
</html>
