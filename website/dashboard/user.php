<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserById($_GET['id']);
$users = getUsers($user);

function printColumnNames()
{
    global $users;
    foreach ($users[0] as $key => $user) { ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($u)
{
    global $user;
    if ($user['Rolle'] == LEHRER) {
        printStudentColumnAsTeacher($u);
    } elseif ($user['Rolle'] == ADMIN) {
        printUserColumnAsAdmin($u);
    }
}

function printUserColumnAsAdmin($user)
{ ?>
    <tr>
        <td><?= $user['Id'] ?></td>
        <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
        <td><?= ROLLEN_NAMEN[$user['Rolle']] ?></td>
        <td><?= getUnlockedText($user) ?></td>
    </tr>
<?php }

function printStudentColumnAsTeacher($user)
{ ?>
    <tr>
        <td><?= $user['Id'] ?></td>
        <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
    </tr>
<?php }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<main>
    <div id="content">
        <table>
            <tr>
                <?php printColumnNames() ?>
            </tr>
            <?php foreach ($users as $u) {
                printEntityRow($u);
            } ?>
        </table>
    </div>
</main>
</body>
</html>
