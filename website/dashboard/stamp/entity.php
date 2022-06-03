<?php
require_once "../../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../../login/login.php");
    exit;
}

$stamp = getStampById($_GET['id']);

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
    <p><b>Id</b>: <?= $stamp['Id'] ?></p>
    <p><b>Text</b>: <?= $stamp['Text'] ?></p>
    <p><b>Empfänger</b>: <a href="../user/entity.php?id=<?= $stamp['Empfänger'] ?>"><?= getUserNameById($stamp['Empfänger']) ?></a</p>
    <p><b>Aussteller</b>: <a href="../user/entity.php?id=<?= $stamp['Aussteller'] ?>"><?= getUserNameById($stamp['Aussteller']) ?></a</p>
    <p><b>Kurs</b>: <a href="../course/entity.php?id=<?= $stamp['Kurs'] ?>"><?= getCourseNameById($stamp['Kurs']) ?></a></p>
    <p><b>Kompetenz</b>: <a href="../../competence.php?id=<?= $stamp['Kompetenz'] ?>"></a><?= getCompetenceNameById($stamp['Kompetenz']) ?></p>
    <p><b>Datum</b>: <?= $stamp['Datum'] ?></p>
</div>
<?php include '../../footer.inc.php'; ?>
</body>
</html>
