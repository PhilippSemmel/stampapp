<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/config.css">
    <title></title>
</head>
<body>
<?php include '../../header.inc.php'; ?>
<?php include 'buttonlist.inc.php'; ?>
<main>
    <div class="table">
        <p><b>Id</b>: <?= $selectedUser['Id'] ?></p>
        <p><b>Name</b>: <?= $selectedUser['Name'] ?></p>
        <p><b>Rolle</b>: <?= ROLLEN_NAMEN[$selectedUser['Rolle']] ?></p>
        <?php if ($selectedUser['Rolle'] == LEHRER) { ?>
            <p><b>Freigeschaltet</b>: <?= getUnlockedText($selectedUser) ?></p>
        <?php } ?>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>