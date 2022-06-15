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
    <table class="table">
        <?php if (isUserAdmin($sessionUser)) { ?>
            <tr>
                <td><b>Id</b></td>
                <td><?= $selectedStamp['Id'] ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td><b>Text</b></td>
            <td><?= $selectedStamp['Text'] ?></td>
        </tr>
        <tr>
            <td><b>Empfänger</b></td>
            <td><a href="../user/entity.php?id=<?= $selectedStamp['Empfänger'] ?>"><?= getUserNameById($selectedStamp['Empfänger']) ?></a></td>
        </tr>
        <tr>
            <td><b>Aussteller</b></td>
            <td><a href="../user/entity.php?id=<?= $selectedStamp['Aussteller'] ?>"><?= getUserNameById($selectedStamp['Aussteller']) ?></a></td>
        </tr>
        <tr>
            <td><b>Kurs</b></td>
            <td><a href="../course/entity.php?id=<?= $selectedStamp['Kurs'] ?>"><?= getCourseNameById($selectedStamp['Kurs']) ?></a></td>
        </tr>
        <tr>
            <td><b>Kompetenz</b></td>
            <td><a href="../../competence.php?id=<?= $selectedStamp['Kompetenz'] ?>"><?= getCompetenceNameById($selectedStamp['Kompetenz']) ?></a></td>
        </tr>
        <tr>
            <td><b>Datum</b></td>
            <td><?= $selectedStamp['Datum'] ?></td>
        </tr>
    </table>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
