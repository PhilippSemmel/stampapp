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
    if (!isUserTeacher($user)) {
        return '';
    }
    if ($user['Freigeschaltet'] == 'true') {
        return 'Ja';
    } else {
        return 'Nein';
    }
}

/**
 * competence functions
 */
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

function getCompetenceNameById($id)
{
    global $db;
    $query =
        'SELECT Name
        FROM Kompetenz 
        WHERE Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $competence = $stmt->fetch();
    return $competence['Name'];
}

function getCompetences()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Kompetenz");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * user functions
 */
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

function getUserNameById($id)
{
    global $db;
    $query =
        'SELECT Name
        FROM Nutzer
        WHERE Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['Name'];
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

function getUserIdByName($name)
{
    global $db;
    $query =
        'SELECT Id
        FROM Nutzer
        WHERE Name = :name';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['Id'];
}

function getUsers($selectedUser)
{
    global $db;
    $parameters = array();
    if (isUserStudent($selectedUser)) {
        $query =
            'SELECT lehrer.Id, lehrer.Name
            FROM Nutzer schüler, Schüler_Kurs sk, Kurs k, Nutzer lehrer
            WHERE schüler.Id = ?
            AND sk.Schüler = schüler.Id
            AND sk.Kurs = k.Id
            AND k.Lehrer = lehrer.Id
            GROUP BY lehrer.Id';
        $parameters[] = $selectedUser['Id'];
    } elseif (isUserTeacher($selectedUser)) {
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

function getUsersForCourse($course)
{
    global $db;
    $query =
        'SELECT n.Id, n.Name
        FROM Nutzer n, Schüler_Kurs sk, Kurs k
        WHERE sk.Schüler = n.Id
        AND sk.Kurs = k.Id
        AND k.Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $course['Id']);
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

function isUserAdmin($user): bool
{
    return $user['Rolle'] == ADMIN;
}

function isUserTeacher($user): bool
{
    return $user['Rolle'] == LEHRER;
}

function isUserStudent($user): bool
{
    return $user['Rolle'] == SCHUELER;
}

/**
 * course functions
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

function getCourseNameById($id)
{
    global $db;
    $query =
        'SELECT Name
        FROM Kurs
        WHERE Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    return $course['Name'];
}


function getCourses($selectedUser, $sessionUser)
{
    global $db;
    $parameters = array();
    if (isUserAdmin($sessionUser)) {
        if (isUserStudent($selectedUser)) {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as Lehrer
                FROM Kurs k, Schüler_Kurs sk, Nutzer l
                WHERE sk.Kurs = k.Id
                AND k.Lehrer = l.Id
                AND sk.Schüler = ?
                GROUP BY k.Id';
            $parameters[] = $selectedUser['Id'];
        } elseif (isUserTeacher($selectedUser)) {
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
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as Lehrer, count(sk.Id) as "Anzahl Schüler"
                FROM Kurs k, Schüler_Kurs sk, Nutzer l
                WHERE sk.Kurs = k.Id
                AND k.Lehrer = l.Id
                GROUP BY k.Id';
        }
    } elseif (isUserTeacher($sessionUser)) {
        $parameters[] = $sessionUser['Id'];
        if ($selectedUser['Id'] == $sessionUser['Id']) {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, count(sk.Id) as "Anzahl Schüler"
                FROM Kurs k, Schüler_Kurs sk, Nutzer l
                WHERE l.Id = ?
                AND k.Lehrer = l.Id
                AND sk.Kurs = k.Id
                GROUP BY k.Id';
        } else {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as Lehrer, count(sk.Id) as "Anzahl Schüler"
                FROM Kurs k, Schüler_Kurs sk, Nutzer l, Schüler_Kurs sk2
                WHERE l.Id = ?
                AND k.Lehrer = l.Id
                AND sk.Kurs = k.Id
                AND sk2.Kurs = sk.Kurs
                AND sk.Schüler = ?
                GROUP BY k.Id';
            $parameters[] = $selectedUser['Id'];
        }
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * stamp functions
 */
function getStampById($id)
{
    global $db;
    $query =
        'SELECT s.Id, s.Text, s.Bild, e.Id as Empfänger, a.Id as Aussteller, k.Id as Kurs, kom.Id as Kompetenz, Datum
        FROM Stempel s, Nutzer e, Nutzer a, Kurs k, Kompetenz kom
        WHERE s.Empfänger = e.Id
        AND s.Aussteller = a.Id
        AND s.Kurs = k.Id
        AND s.Kompetenz = kom.Id
        AND s.Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
}

function getStamps($selectedUser)
{
    global $db;
    $parameter = array();
    if (isUserStudent($selectedUser)) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, aussteller.Id as Aussteller, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE empfänger.Id = ?
            AND s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND s.Kompetenz = kom.Id';
        $parameter[] = $selectedUser['Id'];
    } elseif (isUserTeacher($selectedUser)) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfänger.Id as Empfänger, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND k.Lehrer = ?
            AND s.Kompetenz = kom.Id';
        $parameter[] = $selectedUser['Id'];
    } else {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfänger.Id as Empfänger, aussteller.Id as Aussteller, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND s.Kompetenz = kom.Id';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameter);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStampsForCourse($course, $sessionUser)
{
    global $db;
    $parameters = array();
    $parameters[] = $course['Id'];
    if (isUserStudent($sessionUser)) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, aussteller.Id as Aussteller, kom.Id as Kompetenz, Datum
        FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kompetenz kom
        WHERE s.Kurs = ?
        AND s.Empfänger = empfänger.Id
        AND empfänger.Id = ?
        AND s.Aussteller = aussteller.Id
        AND s.Kompetenz = kom.Id';
        $parameters[] = $sessionUser['Id'];
    } else {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfänger.Id as Empfänger, aussteller.Id as Aussteller, kom.Id as Kompetenz, Datum
        FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kompetenz kom
        WHERE s.Kurs = ?
        AND s.Empfänger = empfänger.Id
        AND s.Aussteller = aussteller.Id
        AND s.Kompetenz = kom.Id';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * request functions
 */
function getRequests($selectedUser)
{
    global $db;
    $parameter = array();
    $query =
        'SELECT a.Id, s.Id as Schüler, k.Id as Kurs 
            FROM Anfrage a, Nutzer s, Kurs k
            WHERE a.Schüler = s.Id
            AND a.Kurs = k.Id';
    if (isUserTeacher($selectedUser)) {
        $query .= ' AND k.Lehrer = ?';
        $parameter[] = $selectedUser['Id'];
    } elseif (isUserStudent($selectedUser)) {
        $query .= ' AND s.Id = ?';
        $parameter[] = $selectedUser['Id'];
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameter);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestsForCourse($selectedCourse)
{
    global $db;
    $query =
        'SELECT a.Id, a.Schüler
        FROM Anfrage a, Kurs k
        WHERE a.Kurs = k.Id
        AND k.Id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $selectedCourse['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}