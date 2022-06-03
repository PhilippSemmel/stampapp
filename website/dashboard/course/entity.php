<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getCourseById($_GET['id']);

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
<?php //include 'buttonlist.inc.php'; ?>
<main>
    <div class="table">
        <p><b>Id</b>: <?= $selectedCourse['Id'] ?></p>
        <p><b>Name</b>: <?= $selectedCourse['Name'] ?></p>
        <p><b>Stufe</b>: <?= $selectedCourse['Stufe'] ?></p>
        <p><b>Fach</b>: <?= $selectedCourse['Fach'] ?></p>
        <p><b>Lehrer</b>: <?= $selectedCourse['Lehrer'] ?></p>
    </div>
</main>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
