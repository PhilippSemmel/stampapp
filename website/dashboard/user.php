<?php

require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

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
                <?php foreach ($users[0] as $key => $user) { ?>
                    <th><?= $key ?></th>
                <?php } ?>
            </tr>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user['Id'] ?></td>
                    <td><?= $user['Name'] ?></td>
                    <td><?= $user['Password'] ?></td>
                    <td><?= $user['Role'] ?></td>
                    <td><?= $user['Unlocked'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
