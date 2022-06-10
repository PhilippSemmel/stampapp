<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$perPage = 10;
$userNumber = getUsersCount($selectedUser);
$totalPages = ceil($userNumber / $perPage);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAt = (int)($perPage * ($page - 1));

$users = getUsers($selectedUser, $startAt, $perPage);

function printPageLinks()
{
    global $page, $totalPages;
    for ($i = 1; $i <= $totalPages; $i++) {
        echo ($i != $page) ? "<a href='user.php?id=" . $_GET['id'] . "&page=$i'>$i</a>" : " $page ";
    }
}

function printColumnNames()
{
    global $users, $sessionUser;
    if (sizeof($users) != 0) {
        if (isUserAdmin($sessionUser)) {
            foreach ($users[0] as $key => $user) { ?>
                <th><?= $key ?></th>
            <?php }
        } elseif (isUserTeacher($sessionUser)) { ?>
            <th>Schüler</th>
        <?php } else { ?>
            <th>Lehrer</th>
        <?php }
    }
}

function printEntityRow($user)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $user['Id'] ?></td>
            <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
            <td><?= ROLLEN_NAMEN[$user['Rolle']] ?></td>
            <td><?= getUnlockedText($user) ?></td>
        <?php } else { ?>
            <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
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
    <table>
        <tr>
            <?php printColumnNames() ?>
        </tr>
        <?php foreach ($users as $user) {
            printEntityRow($user);
        } ?>
        <td><?php printPageLinks() ?></td>
    </table>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
