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
    <table class="table">
        <?php if (isUserAdmin($sessionUser)) { ?>
            <tr>
                <td><b>Id</b></td>
                <td><?= $selectedCourse['Id'] ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td><b>Name</b></td>
            <td><?= $selectedCourse['Name'] ?></td>
        </tr>
        <tr>
            <td><b>Stufe</b></td>
            <td><?= $selectedCourse['Stufe'] ?></td>
        </tr>
        <tr>
            <td><b>Fach</b></td>
            <td><?= $selectedCourse['Fach'] ?></td>
        </tr>
        <tr>
            <td><b>Lehrer</b></td>
            <td><a href="../user/entity.php?id=<?= $selectedCourse['Lehrer'] ?>"><?= getUserNameById($selectedCourse['Lehrer']) ?></a></td>
        </tr>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <tr>
                <td><b>Anzahl Schüler</b></td>
                <td><?= getUsersCountForCourse($selectedCourse['Id']) ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
