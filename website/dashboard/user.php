<?php
require_once "../config.php";
if(!isset($_SESSION["name"])){
    header("Location: ../login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="../style/uebersicht.css" rel="stylesheet">
  </head>
  <body>
    <div id="content">
    <?php
    if(getRank($_SESSION["name"], "../") == ADMIN){
        foreach (getUsers("../") as $value) {
            ?>
            <h1><?= $value["Name"]?></h1>
            <p><?= $value["Role"]?></p>
            <?php
        }
    } else if(getRank($_SESSION["name"], "../") == LEHRER){
        foreach (getUsersByRank(0, "../") as $value) {
            ?>
            <h1><?= $value["Name"]?></h1>
            <p><?= $value["Role"]?></p>
            <?php
        }
    }
    ?>
    </div>
  </body>
</html>
