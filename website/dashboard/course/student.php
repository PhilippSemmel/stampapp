<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getUserById($_GET['id']);
$users = getUsersForCourse($selectedCourse);

function printColumnNames()
{
    global $users, $sessionUser;
    foreach ($users[0] as $key => $selectedCourse) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) {
            continue;
        } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($user)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $user['Id'] ?></td>
        <?php } ?>
        <td><a href="../user/entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
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
<main>
    <div class="table">
        <table>
            <tr>
                <?php printColumnNames() ?>
            </tr>
            <?php foreach ($users as $user) {
                printEntityRow($user);
            } ?>
        </table>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
