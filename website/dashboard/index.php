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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
      <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/config.css">
    <title></title>
  </head>
  <body>
    <?php include '../header.inc.php'; ?>
    <div class="mainbody">
        <iframe name="mainframe" class="mainframe" src="entity.php"></iframe>
    </div>
    <!---------------- BUTTONLIST ---------------->
    <div class="buttonlist">
        <ul>
            <li><a href="entity.php" class="button" target="mainframe">Übersicht</a></li>
            <?php
            if(getRank($_SESSION["name"]) == LEHRER){
            ?>
            <li><a href="user.php" class="button" target="mainframe">Students</a></li>
            <?php } ?>
            <?php
            if(getRank($_SESSION["name"]) == ADMIN){
            ?>
            <li><a href="user.php" class="button" target="mainframe">User</a></li>
            <?php } ?>
            <?php
            if(getRank($_SESSION["name"]) != USER){
            ?>
                <li><a href="courses.php" class="button" target="mainframe">Courses</a></li>
                <li><a href="stamps.php" class="button" target="mainframe">Stamps</a></li>

            <?php
            }
            ?>
            <li><a href="request.php" class="button" target="mainframe">Requests</a></li>
        </ul>
    </div>
  </body>
</html>
