<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
$courses = getCourses($sessionUser, $selectedUser);

function printColumnNames()
{
    global $courses;
    foreach ($courses[0] as $key => $course) { ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($course)
{ ?>
    <tr>
        <?php foreach ($course as $key => $val) {
            if ($key == 'Lehrer') { ?>
                <td><a href='entity.php?id=<?= $val ?>'><?= getUserNameById($val) ?></a></td>
            <?php } elseif ($key == 'Name') { ?>
                <td><a href='../course/entity.php?id=<?= $course['Id'] ?>'><?= $val ?></a></td>
            <?php } else { ?>
                <td><?= $val ?></td>
            <?php }
        } ?>
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
            <?php foreach ($courses as $course) {
                printEntityRow($course);
            } ?>
        </table>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
