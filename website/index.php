<?php
require_once "config.php";

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/config.css">
    <title></title>
  </head>
  <body>
    <?php include 'header.php'; ?>
    <div class="container">
      <div class="competence flex">
        <?php
        foreach (getCompetences() as $value) {
          ?>
          <div class="competence-card flex">
            <h1><?= $value["Name"]?></h1>
            <p><?= $value["Text"]?></p>
          </div>
          <?php
        }
         ?>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </body>
</html>
