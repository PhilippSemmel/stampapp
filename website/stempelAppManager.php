<?php

// ToDo get the correct database in top level folder
$path = 'sqlite:'.dirname(__FILE__).'/StempelApp.db';
$db = new PDO($path);


/**
* @author Silas Beckmann
*
*
*/

/**
 * competence functions
 */
function getCompetences() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM COMPETENCE");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCompetenceById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Competence WHERE Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * user functions
 */


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
function getRank($username): string {
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

function getUserByName($name) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetch();
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

function addNewUser($name, $pw) {
    global $db;
    $stmt = $db->prepare("INSERT INTO User (id, Name, Password, Role) VALUES (null, :id, :pw, 0)");
    $stmt->bindParam(":user", $name);
    $hash = password_hash($pw, PASSWORD_BCRYPT);
    $stmt->bindParam(":pw", $hash);
    $stmt->execute();
}