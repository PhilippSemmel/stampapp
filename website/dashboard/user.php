<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION['name']);
$users = getUsers($user);

function printUserColumn($u)
{
    global $user;
    if ($user['Role'] == LEHRER) {
        printStudentColumnAsTeacher($u);
    } elseif ($user['Role'] == ADMIN) {
        printUserColumnAsAdmin($u);
    }
}

function printUserColumnAsAdmin($u)
{ ?>
    <tr>
        <td><?= $u['Id'] ?></td>
        <td><?= $u['Name'] ?></td>
        <td><?= ROLE_NAMES[$u['Rolle']] ?></td>
        <td><?= getUnlockedText($u) ?></td>
    </tr>
<?php }

function printStudentColumnAsTeacher($u)
{ ?>
    <tr>
        <td><?= $u['Id'] ?></td>
        <td><?= $u['Name'] ?></td>
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
                <?php foreach ($users[0] as $key => $u) { ?>
                    <th><?= $key ?></th>
                <?php } ?>
            </tr>
            <?php foreach ($users as $u) {
                printUserColumn($u);
            } ?>
        </table>
    </div>
</main>
</body>
</html>
