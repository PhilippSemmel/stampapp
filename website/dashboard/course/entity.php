<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getCourseById($_GET['id']);
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
<div class="container flex">
    <div class="table">
        <?php if (isUserAdmin($sessionUser)) { ?>
            <p><b>Id</b>: <?= $selectedCourse['Id'] ?></p>
        <?php } ?>
        <p><b>Name</b>: <?= $selectedCourse['Name'] ?></p>
        <p><b>Stufe</b>: <?= $selectedCourse['Stufe'] ?></p>
        <p><b>Fach</b>: <?= $selectedCourse['Fach'] ?></p>
        <p><b>Lehrer</b>: <a href="../user/entity.php?id=<?= $selectedCourse['Lehrer'] ?>"><?= getUserNameById($selectedCourse['Lehrer']) ?></a></p>
    </div>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
