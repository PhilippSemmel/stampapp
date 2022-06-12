<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedStamp = getStampById($_GET['id']);
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
            <p><b>Id</b>: <?= $selectedStamp['Id'] ?></p>
        <?php } ?>
        <p><b>Text</b>: <?= $selectedStamp['Text'] ?></p>
        <p><b>Empfänger</b>: <a href="../user/entity.php?id=<?= $selectedStamp['Empfänger'] ?>"><?= getUserNameById($selectedStamp['Empfänger']) ?></a</p>
        <p><b>Aussteller</b>: <a href="../user/entity.php?id=<?= $selectedStamp['Aussteller'] ?>"><?= getUserNameById($selectedStamp['Aussteller']) ?></a></p>
        <p><b>Kurs</b>: <a href="../course/entity.php?id=<?= $selectedStamp['Kurs'] ?>"><?= getCourseNameById($selectedStamp['Kurs']) ?></a></p>
        <p><b>Kompetenz</b>: <a href="../../competence.php?id=<?= $selectedStamp['Kompetenz'] ?>"><?= getCompetenceNameById($selectedStamp['Kompetenz']) ?></a></p>
        <p><b>Datum</b>: <?= $selectedStamp['Datum'] ?></p>
    </div>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
