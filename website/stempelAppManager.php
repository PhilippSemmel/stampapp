<?php

/**
* @author Silas Beckmann
*
*
*/
function getCompetences(){
    $mysql = new PDO('sqlite:StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM COMPETENCE");
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}

/**
* @author Silas Beckmann
*
* Zu den bestimmten Zahlen wird ein Name zugeordnet
*/
define("USER", 0);
define("LEHRER", 1);
define("ADMIN", 2);

/**
* @author Silas Beckmann
* @return String: Gibt den Rank von dem Nutzer $username zurück
*
* Erhalte den Rank des Nutzers
*/
function getRank($username, $path){
  $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
  $stmt = $mysql->prepare("SELECT * FROM User WHERE Name = :user");
  $stmt->bindParam(":user", $username);
  $stmt->execute();
  $row = $stmt->fetch();
  return $row["Role"];
}

/**
* @author Silas Beckmann
* @return String: Gibt den Rank von dem Nutzer $username zurück
*
* Erhalte den Rank des Nutzers
*/
function getUsersByRank($rank, $path){
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Role = :rank");
    $stmt->bindParam(":rank", $rank);
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}
function getUserByName($name, $path){
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}
function getUserById($id, $path){
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}
function getUsers($path){
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User");
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}
?>
