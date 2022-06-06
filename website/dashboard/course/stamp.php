<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getCourseById($_GET['id']);
$stamps = getStampsForCourse($selectedCourse, $sessionUser);

function printColumnNames()
{
    global $stamps, $sessionUser;
    foreach ($stamps[0] as $key => $stamp) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) {
            continue;
        } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($stamp)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $stamp['Id'] ?></td>
        <?php } ?>
        <td><a href="../stamp/entity.php?id=<?= $stamp['Id'] ?>"><?= $stamp['Text'] ?></a></td>
        <td><?= $stamp['Bild'] ?></td>
        <?php if (!isUserStudent($sessionUser)) { ?>
        <td><a href="../user/entity.php?id=<?= $stamp['Empfänger'] ?>"><?= getUserNameById($stamp['Empfänger']) ?></a></td>
        <?php } ?>
        <td><a href="../user/entity.php?id=<?= $stamp['Aussteller'] ?>"><?= getUserNameById($stamp['Aussteller']) ?></a></td>
        <td><a href="../../competence.php?id=<?= $stamp['Kompetenz'] ?>"><?= getCompetenceNameById($stamp['Kompetenz']) ?></a></td>
        <td><?= $stamp['Datum'] ?></td>
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
