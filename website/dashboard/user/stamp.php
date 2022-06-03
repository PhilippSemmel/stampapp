<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$stamps = getStamps($sessionUser, $selectedUser);

function printColumnNames()
{
    global $stamps;
    foreach ($stamps[0] as $key => $stamp) { ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($stamp)
{ ?>
    <tr>
        <?php foreach ($stamp as $key => $val) {
            if ($key == 'Empfänger' || $key == 'Aussteller') {
                $user = getUserByName($val); ?>
                <td><a href='entity.php?id=<?= $user['Id'] ?>'><?= $user['Name'] ?></a></td>
            <?php } elseif ($key == 'Kurs') {
                $course = getCourseById($val); ?>
                <td><a href='../course/entity.php?id=<?= $course['Id'] ?>'><?= $course['Name'] ?></a></td>
            <?php } else { ?>
                <td><?= $val ?></td>
            <?php } ?>
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
            <?php foreach ($stamps as $stamp) {
                printEntityRow($stamp);
            } ?>
        </table>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
