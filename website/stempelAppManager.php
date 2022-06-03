<?php
session_start();

$db = new PDO('sqlite:' . dirname(__FILE__) . '/StempelApp.db');


/**
 * constants
 */
const SCHUELER = 0;
const LEHRER = 1;
const ADMIN = 2;
const ROLLEN_NAMEN = array(0 => 'Schüler', 1 => 'Lehrer', 2 => 'Admin');

function getUnlockedText($user): string
{
    if ($user['Rolle'] != LEHRER) {
        return '';
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

function getUserById($id)
{
    global $db;
    $query =
        'SELECT *
        FROM Nutzer
        WHERE Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers($sessionUser, $selectedUser)
{
    global $db;
    $parameters = array();
    if ($selectedUser['Rolle'] == LEHRER) {
        $query =
                'SELECT n.Id, n.Name
            FROM Nutzer n, Schüler_Kurs sk, Kurs k
            WHERE sk.Schüler = n.Id
            AND sk.Kurs = k.Id
            AND k.Lehrer = ?';
        $parameters[] = $selectedUser['Id'];
    } else {
        $query =
            'SELECT n.Id, n.Name, n.Rolle, n.Freigeschaltet
        FROM Nutzer n';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
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
function getCourseById($id)
{
    global $db;
    $query =
        'SELECT *
        FROM Kurs k
        WHERE k.Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
}


function getCourses($sessionUser, $selectedUser)
{
    global $db;
    $parameters = array();
    if ($selectedUser['Rolle'] == LEHRER) {
        $query =
            'SELECT k.Id, k.Name, k.Stufe, k.Fach, count(sk.Id) as "Anzahl Schüler"
            FROM Kurs k, Schüler_Kurs sk, Nutzer l
            WHERE l.Id = ?
            AND k.Lehrer = l.Id
            AND sk.Kurs = k.Id
            GROUP BY k.Id';
        $parameters[] = $selectedUser['Id'];
    } else {
        $query =
            'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Name as Lehrer, count(sk.Id) as "Anzahl Schüler"
            FROM Kurs k, Schüler_Kurs sk, Nutzer l
            WHERE sk.Kurs = k.Id
            AND k.Lehrer = l.Id
            GROUP BY k.Id';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Stamp functions
 */
function getStamps($sessionUser, $selectedUser)
{
    global $db;
    $parameter = array();
    if ($selectedUser['Rolle'] == LEHRER) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfäger.Name as Empfänger, k.Id as Kurs, kom.Name as Kompetenz, Datum
            FROM Stempel s, Nutzer empfäger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfäger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND k.Lehrer = ?
            AND s.Kompetenz = kom.Id';
        $parameter[] = $selectedUser['Id'];
    } else {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfäger.Name as Empfänger, aussteller.Name as Aussteller, k.Id as Kurs, kom.Name as Kompetenz, Datum
            FROM Stempel s, Nutzer empfäger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfäger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND s.Kompetenz = kom.Id';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameter);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Request functions
 */
function getRequests($sessionUser, $selectedUser)
{
    global $db;
    $parameter = array();
    $query =
        'SELECT a.Id, s.Name as Schüler, k.Id as Kurs 
            FROM Anfrage a, Nutzer s, Kurs k
            WHERE a.Schüler = s.Id
            AND a.Kurs = k.Id';
    if ($selectedUser['Rolle'] == LEHRER) {
        $query .= ' AND k.Lehrer = ?';
        $parameter[] = $selectedUser['Id'];
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameter);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}