<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$requests = getRequests($sessionUser, $selectedUser);

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
            if ($key == 'Schüler') { ?>
                <td><a href="entity.php?id=<?= $val ?>"><?= getUserNameById($val) ?></a></td>
            <?php } elseif ($key == 'Kurs') { ?>
                <td><a href='../course/entity.php?id=<?= $val ?>'><?= getCourseNameById($val) ?></a></td>
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
            <?php foreach ($requests as $request) {
                printEntityRow($request);
            } ?>
        </table>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
