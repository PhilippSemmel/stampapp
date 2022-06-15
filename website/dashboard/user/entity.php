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
<div class="container flex">
    <table class="table">
        <?php if (isUserAdmin($sessionUser)) { ?>
            <tr>
                <td><b>Id</b></td>
                <td><?= $selectedUser['Id'] ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td><b>Name</b></td>
            <td><?= $selectedUser['Name'] ?></td>
        </tr>
        <tr>
            <td><b>Rolle</b></td>
            <td><?= ROLLEN_NAMEN[$selectedUser['Rolle']] ?></td>
        </tr>
        <?php // stats ?>
        <?php if (isUserTeacher($selectedUser)) { ?>
            <tr>
                <td><b>Freigeschaltet</b></td>
                <td><?= getUnlockedText($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Schüler</b></td>
                <td><?= getUsersCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Kurse</b></td>
                <td><?= getCoursesCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Stempel</b></td>
                <td><?= getStampsCount($selectedUser) ?></td>
            </tr>
        <?php } elseif (isUserStudent($selectedUser)) { ?>
            <tr>
                <td><b>Anzahl Lehrer</b></td>
                <td><?= getUsersCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Kurse</b></td>
                <td><?= getCoursesCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Stempel</b></td>
                <td><?= getStampsCount($selectedUser) ?></td>
            </tr>
        <?php } elseif (isUserAdmin($sessionUser)) { ?>
            <tr>
                <td><b>Anzahl Nutzer</b></td>
                <td><?= getUsersCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Lehrer</b></td>
                <td><?= getTeacherCount() ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Schüler</b></td>
                <td><?= getStudentCount() ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Kurse</b></td>
                <td><?= getCoursesCount($selectedUser) ?></td>
            </tr>
            <tr>
                <td><b>Durchschnittliche Schüler pro Kurs</b></td>
                <td><?= getAverageUsersPerCourse() ?></td>
            </tr>
            <tr>
                <td><b>Durchschnittliche Stempel pro Kurs</b></td>
                <td><?= getAverageStampsPerCourse() ?></td>
            </tr>
            <tr>
                <td><b>Anzahl Stempel</b></td>
                <td><?= getStampsCount($selectedUser) ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
