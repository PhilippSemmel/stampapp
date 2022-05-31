<?php
session_start();

$db = new PDO('sqlite:' . dirname(__FILE__) . '/StempelApp.db');


/**
 * constants
 */
const USER = 0;
const LEHRER = 1;
const ADMIN = 2;
const ROLE_NAMES = array(0 => 'Schüler', 1 => 'Lehrer', 2 => 'Admin');

function getUnlockedText($user): string
{
    if ($user['Role'] != LEHRER) {
        return 'Ja';
    }
    if ($user['Unlocked'] || $user['Freigeschaltet']) {
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
    $stmt = $db->prepare("SELECT * FROM COMPETENCE");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCompetenceById($id)
{
    global $db;
    $query =
        'SELECT *
        FROM Competence 
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
        FROM User 
        WHERE Name = :user';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user", $username);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row["Role"];
}

function getUserByName($name)
{
    global $db;
    $query =
        'SELECT *
        FROM User
        WHERE Name = :name';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers($user)
{
    if ($user['Role'] == LEHRER) {
        return getStudentsAsTeacher($user);
    } elseif ($user['Role'] == ADMIN) {
        return getUsersAsAdmin();
    }
}


function getUsersAsAdmin()
{
    global $db;
    $query =
        'SELECT u.Id, u.Name, u.Role Rolle, u.Unlocked Freigeschaltet
        FROM User u';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getStudentsAsTeacher($user)
{
    global $db;
    $query =
        'SELECT s.Id, s.Name
        FROM User t, User s, Course c, Student_Course sc
        WHERE t.Id = :id
        AND c.Teacher = t.Id
        AND sc.Course = c.Id
        AND sc.Student = s.Id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $user['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addNewUser($name, $pw)
{
    global $db;
    $query =
        'INSERT INTO User (id, Name, Password, Role, Unlocked)
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
    if ($user['Role'] == USER) {
        return getCoursesAsUser($user);
    } elseif ($user['Role'] == LEHRER) {
        return getCoursesAsTeacher($user);
    } elseif ($user['Role'] == ADMIN) {
        return getCoursesAsAdmin();
    }
}

function getCoursesAsAdmin()
{
    global $db;
    $query =
        'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach, t.Name Lehrer
        FROM Course c, User t
        WHERE c.Teacher = t.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCoursesAsTeacher($user)
{
    global $db;
    $query =
        'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach
        FROM Course c, User t
        WHERE t.Id = :id
        AND c.Teacher = t.Id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $user['Id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCoursesAsUser($user)
{
    global $db;
    $query =
        'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach, t.Name Lehrer
        FROM Course c, User t, Student_Course sc
        WHERE c.Teacher = t.Id
        AND sc.Course = c.Id
        AND sc.Student = :id';
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
        'SELECT s.Id, s.Text, s.Image Bild, r.Name Empfänger, i.Name Aussteller, c.Name Kurs, com.Name Kompetenz, Date Datum
        FROM Stamp s, User r, User i, Course c, Competence com
        WHERE s.Receiver = r.Id
        AND s.Issuer = i.Id
        AND s.Course = c.Id
        AND s.Competence = com.Id';
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
        'SELECT r.Id, s.Name Schüler, c.Name Kurs 
        FROM Request r, User s, Course c
        WHERE r.Student = s.Id
        AND r.Course = c.Id';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}