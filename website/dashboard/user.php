<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION['name']);
$users = getUsers();
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
            <?php foreach ($users as $u) { ?>
                <tr>
                    <td><?= $u['Id'] ?></td>
                    <td><?= $u['Name'] ?></td>
                    <td><?= $u['Password'] ?></td>
                    <td><?= $u['Role'] ?></td>
                    <td><?= $u['Unlocked'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
