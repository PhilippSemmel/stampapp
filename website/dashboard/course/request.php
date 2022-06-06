<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getCourseById($_GET['id']);
$requests = getRequestsForCourse($selectedCourse);

function printColumnNames()
{
    global $requests, $sessionUser;
    foreach ($requests[0] as $key => $request) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) {
            continue;
        } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($request)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $request['Id'] ?></td>
        <?php } ?>
        <td><a href="../user/entity.php?id=<?= $request['Schüler'] ?>"><?= getUserNameById($request['Schüler']) ?></a></td>
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
            <?php foreach ($requests as $request) {
                printEntityRow($request);
            } ?>
        </table>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
