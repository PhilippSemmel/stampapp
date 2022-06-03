<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../../login/login.php");
    exit;
}

$request = getRequestById($_GET['id']);

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
<div id="content">
<p><b>Id</b>: <?= $request['Id'] ?></p>
<p><b>Schüler</b>: <a href="../user/entity.php?id=<?= $request['Schüler'] ?>"><?= getUserNameById($request['Schüler']) ?></a></p>
<p><b>Kurs</b>: <a href="../course/entity.php?id=<?= $request['Kurs'] ?>"><?= getCourseNameById($request['Kurs']) ?></a></p>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
