<?php
$path = 'sqlite:StempelApp.db';
$db = new PDO($path);
/**
* @author Silas Beckmann
*
*
*/
function getCompetences() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM COMPETENCE");
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
function getRank($username): string
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Name = :user");
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
function getUsersByRank($rank) : String {
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Role = :rank");
    $stmt->bindParam(":rank", $rank);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUsersByName($name) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUserById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getUsers() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM User");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCompetenceById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Competence c WHERE c.Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}
