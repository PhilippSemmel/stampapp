<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserById($_GET['id']);
$requests = getRequests($user);

function printColumnNames()
{
    global $requests;
    foreach ($requests[0] as $key => $request) { ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($request)
{ ?>
    <tr>
        <?php foreach ($request as $key => $val) {
            if ($key == 'Schüler') {
                $user = getUserByName($val) ?>
                <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
            <?php } else { ?>
            <td><?= $val ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <?php
}

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
            <?php foreach ($requests as $request) {
                printEntityRow($request);
            } ?>
        </table>
    </div>
</main>
</body>
</html>
