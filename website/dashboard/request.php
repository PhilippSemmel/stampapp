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
        h
    </div>
  </body>
</html>