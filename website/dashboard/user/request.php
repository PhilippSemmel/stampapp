<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$requests = getRequests($selectedUser);

function printColumnNames()
{
    global $requests, $sessionUser;
    if (sizeof($requests) != 0) {
        foreach ($requests[0] as $key => $request) {
            if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
            <th><?= $key ?></th>
        <?php }
    }
}

function printEntityRow($request)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $request['Id'] ?></td>
        <?php } ?>
        <?php if (!isUserTeacher($sessionUser)) { ?>
            <td><a href="entity.php?id=<?= $request['Schüler'] ?>"><?= getUserNameById($request['Schüler']) ?></a></td>
        <?php } else { ?>
            <td><?= getUserNameById($request['Schüler']) ?></td>
        <?php } ?>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <td><a href='../course/entity.php?id=<?= $request['Kurs'] ?>'><?= getCourseNameById($request['Kurs']) ?></a></td>
        <?php } else { ?>
            <td><?= getCourseNameById($request['Kurs']) ?></td>
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
            <?php foreach ($requests as $request) {
                printEntityRow($request);
            } ?>
        </table>
    </div>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
