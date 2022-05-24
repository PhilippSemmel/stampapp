<?php 
require_once "../config.php";
if(!isset($_SESSION["name"])){
    header("Location: ../login/login.php");
    exit;
}
$userTo = getUserById($_GET["id"], "../");
$userFrom = getUserById($_SESSION["name"], "../");

if($userFrom["Rank"] == LEHRER && $userTo["Rank"] == LEHRER){
    header("Location: ../index.php");
    exit;
}
if($userFrom["Rank"] == USER){
    header("Location: ../index.php");
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
    <p class="price"><?php $userTo["Name"]?></p>
    </div>
  </body>
</html>