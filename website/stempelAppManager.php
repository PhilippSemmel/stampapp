<?php

/**
* @author Silas Beckmann
*
*
*/
function getCompetences() {
    $mysql = new PDO('sqlite:StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM COMPETENCE");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
* @author Silas Beckmann
*
* Zu den bestimmten Zahlen wird ein Name zugeordnet
*/
const USER = 0;
const LEHRER = 1;
const ADMIN = 2;

/**
* @author Silas Beckmann
* @return String: Gibt den Rank von dem Nutzer $username zurück
*
* Erhalte den Rank des Nutzers
*/
function getRank($username, $path): string
{
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
function getUsersByRank($rank, $path) : String {
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Role = :rank");
    $stmt->bindParam(":rank", $rank);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUserByName($name, $path) {
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUserById($id, $path) {
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUsers($path) {
    $mysql = new PDO('sqlite:'.$path.'/StempelApp.db');
    $stmt = $mysql->prepare("SELECT * FROM User");
    $stmt->execute();
    return $stmt->fetchAll();
}

