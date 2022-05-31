<?php
session_start();

$db = new PDO('sqlite:' . dirname(__FILE__) . '/StempelApp.db');


/**
 * constants
 */
const NUTZER = 0;
const LEHRER = 1;
const ADMIN = 2;
const ROLLEN_NAMEN = array(0 => 'Schüler', 1 => 'Lehrer', 2 => 'Admin');

function getUnlockedText($user): string
{
    if ($user['Rolle'] != LEHRER) {
        return 'Ja';
    }
    if ($user['Freigeschaltet']) {
        return 'Ja';
    } else {
        return 'Nein';
    }
}

/**
 * competence functions
 */
function getCompetences()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Kompetenz");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCompetenceById($id)
{
    global $db;
    $query =
        'SELECT *
        FROM Kompetenz 
        WHERE Id = :id';
    $stmt = $db->prepare($query);
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
    $query =
        'SELECT * 
        FROM Nutzer 
        WHERE Name = :nutzer';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":nutzer", $username);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['Rolle'];
}

function getUserByName($name)
{
    global $db;
    $query =
        'SELECT *
        FROM Nutzer
        WHERE Name = :name';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers($user)
{
    if ($user['Rolle'] == LEHRER) {
        return getStudentsAsTeacher($user);
    } elseif ($user['Rolle'] == ADMIN) {
        return getUsersAsAdmin();
    }
    return array();
}


function getUsersAsAdmin()
{
    global $db;
    $query =
        'SELECT n.Id, n.Name, n.Rolle, n.Freigeschaltet
        FROM Nutzer n';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getStudentsAsTeacher($user)
{
    global $db;
    $query =
        'SELECT s.Id, s.Name
        FROM Nutzer l, Nutzer s, Kurs k, Schüler_Kurs sk
        WHERE l.Id = :id
        AND k.Lehrer = l.Id
        AND sk.Kurs = k.Id
        AND sk.Schüler = s.Id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $user['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addNewUser($name, $pw)
{
    global $db;
    $query =
        'INSERT INTO Nutzer (id, Name, Passwort, Rolle, Freigeschaltet)
        VALUES (null, :name, :pw, 0, false)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $name);
    $hash = password_hash($pw, PASSWORD_BCRYPT);
    $stmt->bindParam(":pw", $hash);
    $stmt->execute();
}

/**
 * Course functions
 */
function getCourses($user)
{
    if ($user['Rolle'] == NUTZER) {
        return getCoursesAsUser($user);
    } elseif ($user['Rolle'] == LEHRER) {
        return getCoursesAsTeacher($user);
    } elseif ($user['Rolle'] == ADMIN) {
        return getCoursesAsAdmin();
    }
    return array();
}

function getCoursesAsAdmin()
{
    global $db;
    $query =
        'SELECT k.Id, k.Name, k.Stufe, k.Fach, count(sk.Id) as "Anzahl Schüler"
        FROM Kurs k, Schüler_Kurs sk
        WHERE sk.Kurs = k.Id
        GROUP BY k.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCoursesAsTeacher($user)
{
    global $db;
    $query =
        'SELECT k.Id, k.Name, k.Stufe, k.Fach, count(sk.Id) as "Anzahl Schüler"
        FROM Kurs k, Nutzer l, Schüler_Kurs sk
        WHERE l.Id = :id
        AND k.Lehrer = l.Id
        AND sk.Kurs = k.Id
        GROUP BY k.Id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $user['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCoursesAsUser($user)
{
    global $db;
    $query =
        'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Name as Lehrer
        FROM Kurs k, Nutzer l, Schüler_Kurs sk
        WHERE k.Lehrer = l.Id
        AND sk.Kurs = k.Id
        AND sk.Schüler = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $user['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Course functions
 */
function getStamps()
{
    global $db;
    $query =
        'SELECT s.Id, s.Text, s.Bild, e.Name as Empfänger, a.Name as Aussteller, k.Name as Kurs, kom.Name as Kompetenz, Datum
        FROM Stempel s, Nutzer e, Nutzer a, Kurs k, Kompetenz kom
        WHERE s.Empfänger = e.Id
        AND s.Aussteller = a.Id
        AND s.Kurs = k.Id
        AND s.Kompetenz = kom.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Request functions
 */
function getRequests()
{
    global $db;
    $query =
        'SELECT r.Id, s.Name as Schüler, k.Name as Kurs 
        FROM Anfrage r, Nutzer s, Kurs k
        WHERE r.Schüler = s.Id
        AND r.Kurs = k.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}