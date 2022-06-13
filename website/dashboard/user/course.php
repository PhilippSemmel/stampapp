<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$courses = getCourses($selectedUser, $sessionUser);

function printColumnNames()
{
    global $courses, $sessionUser;
    if (sizeof($courses) != 0) {
        foreach ($courses[0] as $key => $course) {
            if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
            <th><?= $key ?></th>
        <?php }
    }
}

function printEntityRow($course)
{
    global $sessionUser, $selectedUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $course['Id'] ?></td>
        <?php } ?>
        <td><a href="../course/entity.php?id=<?= $course['Id'] ?>"><?= $course['Name'] ?></a></td>
        <td><?= $course['Stufe'] ?></td>
        <td><?= $course['Fach'] ?></td>
        <?php if (!isUserTeacher($sessionUser)) { ?>
            <td><a href='entity.php?id=<?= $course['Lehrer'] ?>'><?= getUserNameById($course['Lehrer']) ?></a></td>
        <?php } ?>
        <?php if (!isUserStudent($selectedUser)) { ?>
            <td><?= $course['Anzahl Schüler'] ?></td>
        <?php } ?>
    </tr>
<?php }

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
        <table>
            <tr>
                <?php printColumnNames() ?>
            </tr>
            <?php foreach ($courses as $course) {
                printEntityRow($course);
            } ?>
        </table>
    </div>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
