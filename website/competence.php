<?php
require_once "config.php";

if(isset($_GET["id"])) {
    $competence = getCompetenceById($_GET["id"]);
} else {
    $competence = array ('Id' => 0, 'Name' => 'Error', 'Text' => 'Error Error');
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/config.css">
    <title></title>
</head>
    <body>
        <?php include 'header.inc.php'; ?>
        <h1><?= $competence['Name'] ?></h1>
        <?php include 'footer.inc.php'; ?>
    </body>
</html>