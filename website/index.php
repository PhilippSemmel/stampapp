<?php
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
            <link rel="stylesheet" href="css/index.css">
            <link rel="stylesheet" href="css/header.css">
            <link rel="stylesheet" href="css/footer.css">
            <link rel="stylesheet" href="css/config.css">
        <title></title>
    </head>
    <body>
        <?php include 'header.inc.php'; ?>
        <div class="container">
            <div class="competence flex">
                <?php foreach (getCompetences() as $competence) { ?>
                    <div class="competence-card flex">
                        <a class="competence-text" href="/competence.php?id=<?= $competence["Id"]?>">
                            <h1><?= $competence["Name"]?></h1>
                            <p> <?= $competence["Text"]?></p>
                        </a>
                    </div>
                <?php }  ?>
            </div>
        </div>
        <?php include 'footer.inc.php'; ?>
    </body>
</html>
