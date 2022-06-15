<?php
session_start();

$db = new PDO('sqlite:' . dirname(__FILE__) . '/StempelApp.db');

/**
 * constants
 */
const ENTITIES_PER_PAGE = 10;
const SCHUELER = 0;
const LEHRER = 1;
const ADMIN = 2;
const ROLLEN_NAMEN = array(0 => 'Schüler', 1 => 'Lehrer', 2 => 'Admin');

function getUnlockedText($user): string
{
    if (!isUserTeacher($user)) {
        return '';
    }
    if ($user['Freigeschaltet'] == 1) {
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

function getUsers($selectedUser, $startAtEntity = null)
{
    global $db;
    $parameters = array();
    if (isUserStudent($selectedUser)) {
        $query =
            'SELECT lehrer.Id, lehrer.Name, lehrer.Rolle
            FROM Nutzer schüler, Schüler_Kurs sk, Kurs k, Nutzer lehrer
            WHERE schüler.Id = ?
            AND sk.Schüler = schüler.Id
            AND sk.Kurs = k.Id
            AND k.Lehrer = lehrer.Id
            GROUP BY lehrer.Id';
        $parameters[] = $selectedUser['Id'];
    } elseif (isUserTeacher($selectedUser)) {
        $query =
            'SELECT n.Id, n.Name, n.Rolle
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
    // add page limitations
    if (is_integer($startAtEntity)) {
        $parameters = array_merge($parameters, [$startAtEntity, ENTITIES_PER_PAGE]);
        $query .= ' LIMIT ?, ?';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersForCourse($course, $startAtEntity)
{
    global $db;
    $query =
        'SELECT n.Id, n.Name
        FROM Nutzer n, Schüler_Kurs sk, Kurs k
        WHERE sk.Schüler = n.Id
        AND sk.Kurs = k.Id
        AND k.Id = ?
        LIMIT ?, ?';
    $stmt = $db->prepare($query);
    $stmt->execute(array($course['Id'], $startAtEntity, ENTITIES_PER_PAGE));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersCount($selectedUser): int
{
    $users = getUsers($selectedUser);
    return count($users);
}

function getTeacherCount(): int
{
    global $db;
    $query =
        'SELECT count(n.Id) as count
        FROM Nutzer n
        WHERE n.Rolle = 1';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function getStudentCount(): int
{
    global $db;
    $query =
        'SELECT count(n.Id) as count
        FROM Nutzer n
        WHERE n.Rolle = 0';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function getUsersCountForCourse($course): int
{
    global $db;
    $query =
        'SELECT count(n.Id) as count
        FROM Nutzer n, Schüler_Kurs sk, Kurs k
        WHERE sk.Schüler = n.Id
        AND sk.Kurs = k.Id
        AND k.Id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute(array($course['Id']));
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function getAverageUsersPerCourse(): int
{
    global $db;
    $query =
        'SELECT count(n.Name) / count(distinct(k.Id)) AS count
        FROM Nutzer n, Schüler_Kurs sk, Kurs k
        WHERE sk.Schüler = n.Id
        AND sk.Kurs = k.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function addNewUser($name, $pw)
{
    global $db;
    $hash = password_hash($pw, PASSWORD_BCRYPT);
    $query =
        'INSERT INTO Nutzer (Id, Name, Passwort, Rolle, Freigeschaltet)
        VALUES (null, ?, ?, 0, 0)';
    $stmt = $db->prepare($query);
    $stmt->execute(array($name, $hash));
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

function isSameUser($user1, $user2): bool
{
    return $user1['Id'] == $user2['Id'];
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

function getCourses($selectedUser, $startAtEntity = null)
{
    global $db;
    $sessionUser = getUserByName($_SESSION['name']);
    $parameters = array();
    if (isUserAdmin($sessionUser)) {
        if (isUserStudent($selectedUser)) {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as Lehrer, count(sk.Id) as "Anzahl Schüler"
                FROM Kurs k, Schüler_Kurs sk, Nutzer l
                WHERE sk.Kurs = k.Id
                AND k.Lehrer = l.Id
                AND sk.Schüler = ?
                GROUP BY k.Id';
            $parameters[] = $selectedUser['Id'];
        } elseif (isUserTeacher($selectedUser)) {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as Lehrer, count(sk.Id) as "Anzahl Schüler"
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
        if (isSameUser($sessionUser, $selectedUser)) {
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
    } elseif (isUserStudent($sessionUser)) {
        $parameters[] = $sessionUser['Id'];
        if (isSameUser($sessionUser, $selectedUser) or isUserTeacher($selectedUser)) {
            $query =
                'SELECT k.Id, k.Name, k.Stufe, k.Fach, l.Id as "Lehrer"
                FROM Kurs k, Schüler_Kurs sk, Nutzer s, Nutzer l
                WHERE s.Id = ?
                AND sk.Kurs = k.Id
                AND sk.Schüler = s.Id
                AND k.Lehrer = l.Id';
        }
    }
    // add page limitations
    if (is_integer($startAtEntity)) {
        $parameters = array_merge($parameters, [$startAtEntity, ENTITIES_PER_PAGE]);
        $query .= ' LIMIT ?, ?';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCoursesCount($selectedUser): int
{
    $courses = getCourses($selectedUser);
    return count($courses);
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

function getStamps($selectedUser, $startAtEntity = null)
{
    global $db;
    $sessionUser = getUserByName($_SESSION['name']);
    $parameters = array();
    if (isUserStudent($selectedUser)) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, aussteller.Id as Aussteller, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE empfänger.Id = ?
            AND s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND s.Kompetenz = kom.Id';
        $parameters[] = $selectedUser['Id'];
        if (isUserTeacher($sessionUser)) {
            $query .= ' AND k.Lehrer = ?';
            $parameters[] = $sessionUser['Id'];
        }
    } elseif (isUserTeacher($selectedUser)) {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfänger.Id as Empfänger, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND k.Lehrer = ?
            AND s.Kompetenz = kom.Id';
        $parameters[] = $selectedUser['Id'];
        if (isUserStudent($sessionUser)) {
            $query .= ' AND empfänger.Id = ?';
            $parameters[] = $sessionUser['Id'];
        }
    } else {
        $query =
            'SELECT s.Id, s.Text, s.Bild, empfänger.Id as Empfänger, aussteller.Id as Aussteller, k.Id as Kurs, kom.Id as Kompetenz, Datum
            FROM Stempel s, Nutzer empfänger, Nutzer aussteller, Kurs k, Kompetenz kom
            WHERE s.Empfänger = empfänger.Id
            AND s.Aussteller = aussteller.Id
            AND s.Kurs = k.Id
            AND s.Kompetenz = kom.Id';
    }
    // add page limitations
    if (is_integer($startAtEntity)) {
        $parameters = array_merge($parameters, [$startAtEntity, ENTITIES_PER_PAGE]);
        $query .= ' LIMIT ?, ?';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStampsForCourse($course, $startAtEntity)
{
    global $db;
    $sessionUser = getUserByName($_SESSION['name']);
    $parameters = array($course['Id']);
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
    // add page limitations
    $parameters = array_merge($parameters, [$startAtEntity, ENTITIES_PER_PAGE]);
    $query .= ' LIMIT ?, ?';
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStampsCount($selectedUser): int
{
    $stamps = getStamps($selectedUser);
    return count($stamps);
}

function getStampsCountForCourse($course): int
{
    global $db;
    $sessionUser = getUserByName($_SESSION['name']);
    $parameters = array($course['Id']);
    $query =
        'SELECT count(s.Id) AS count
        FROM Stempel s
        WHERE s.Kurs = ?';
    if ($sessionUser['Rolle'] = SCHUELER) {
        $query .= ' AND s.Empfänger = ?';
        $parameters[] = array($sessionUser['Id']);
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function getAverageStampsPerCourse(): int
{
    global $db;
    $query =
        'SELECT count(s.Id) / count(distinct(s.Kurs)) as count
        FROM Stempel s';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

/**
 * request functions
 */
function getRequests($selectedUser, $startAtEntity = null)
{
    global $db;
    $parameters = array();
    $query =
        'SELECT a.Id, s.Id as Schüler, k.Id as Kurs
            FROM Anfrage a, Nutzer s, Kurs k
            WHERE a.Schüler = s.Id
            AND a.Kurs = k.Id';
    if (isUserTeacher($selectedUser)) {
        $query .= ' AND k.Lehrer = ?';
        $parameters[] = $selectedUser['Id'];
    } elseif (isUserStudent($selectedUser)) {
        $query .= ' AND s.Id = ?';
        $parameters[] = $selectedUser['Id'];
    }
    // add page limitations
    if (is_integer($startAtEntity)) {
        $parameters = array_merge($parameters, [$startAtEntity, ENTITIES_PER_PAGE]);
        $query .= ' LIMIT ?, ?';
    }
    $stmt = $db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestsForCourse($selectedCourse, $startAtEntity)
{
    global $db;
    $query =
        'SELECT a.Id, a.Schüler
        FROM Anfrage a, Kurs k
        WHERE a.Kurs = k.Id
        AND k.Id = ?
        LIMIT ?, ?';
    $stmt = $db->prepare($query);
    $stmt->execute(array($selectedCourse['Id'], $startAtEntity, ENTITIES_PER_PAGE));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestsCountForCourse($course): int
{
    global $db;
    $query =
        'SELECT count(a.Id) as count
        FROM Anfrage a
        WHERE a.Kurs = ?';
    $stmt = $db->prepare($query);
    $stmt->execute(array($course['Id']));
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    return $count['count'];
}

function getRequestCount($selectedUser): int
{
    $requests = getRequests($selectedUser);
    return count($requests);
}