<?php
session_start();

$db = new PDO('sqlite:' . dirname(__FILE__) . '/StempelApp.db');

const USER = 0;
const LEHRER = 1;
const ADMIN = 2;

/**
 * competence functions
 */
function getCompetences()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM COMPETENCE");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCompetenceById($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Competence WHERE Id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * user functions
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

function getUsersByRank($rank): string
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Role = :rank");
    $stmt->bindParam(":rank", $rank);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserByName($name)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE Name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User");
    $stmt->execute();
    return $stmt->fetchAll();
}

function addNewUser($name, $pw)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO User (id, Name, Password, Role, Unlocked) VALUES (null, :id, :pw, 0, :unlocked)");
    $stmt->bindParam(":user", $name);
    $hash = password_hash($pw, PASSWORD_BCRYPT);
    $stmt->bindParam(":pw", $hash);
    $stmt->execute();
}