<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION['name']);

function getRoleName($roleId): string
{
    $names = array(0 => 'Schüler', 1 => 'Lehrer', 2 => 'Admin');
    return $names[$roleId];
}

function getUnlockedText($unlocked): string
{
    if (!$unlocked) {
        return 'Nein';
    } else {
        return 'Ja';
    }
}


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
    <p><b>Passwort</b>: <?= $user['Password'] ?></p>
    <p><b>Rolle</b>: <?= getRoleName($user['Role']) ?></p>
    <p><b>Freigeschaltet</b>: <?= getUnlockedText($user['Unlocked']) ?></p>
</div>
</body>
</html>
