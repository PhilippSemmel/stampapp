<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$users = getUsers($selectedUser);

function printColumnNames()
{
    global $users, $sessionUser;
    if ($sessionUser['Rolle'] == ADMIN) {
        foreach ($users[0] as $key => $user) { ?>
            <th><?= $key ?></th>
        <?php }
    } elseif ($sessionUser['Rolle'] == LEHRER) { ?>
        <th>Schüler</th>
    <?php } else { ?>
        <th>Lehrer</th>
    <?php }
}

function printEntityRow($user)
{
    global $sessionUser ?>
    <tr>
        <?php if ($sessionUser['Rolle'] == ADMIN) { ?>
            <td><?= $user['Id'] ?></td>
            <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
            <td><?= ROLLEN_NAMEN[$user['Rolle']] ?></td>
            <td><?= getUnlockedText($user) ?></td>
        <?php } elseif ($sessionUser['Rolle'] == LEHRER || $sessionUser['Rolle'] == SCHUELER) { ?>
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
