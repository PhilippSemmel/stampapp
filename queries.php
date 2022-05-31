<?php
# $_SESSION = array(id, name, password, role, unlocked)
# $selectedEntity

# admin all info
// main page
$query =
    'SELECT Id, Name, Password Passwort, Role Rolle, Unlocked Freigeschaltet
    FROM User u
    WHERE u.Id = :userid';

// user table
$query =
    'SELECT Id, Name, Password Passwort, Role Rolle, Unlocked Freigeschaltet
    FROM User u
    ORDER BY u.Id';

// course table
$query = 
    'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach, COUNT(c.Name) as "Schüler Anzahl", t.Name Lehrer
    FROM Course c, Student_Course sc, User s, User t
    WHERE sc.Course = c.Id
    AND sc.Student = s.Id
    AND s.Role = 2
    AND c.Teacher = t.Id
    GROUP BY c.Id';

// stamp table
$query = 
    'SELECT s.Id, s.Text, s.Image Bild, u1.Name Schüler, u2.Name Lehrer, c.Name Kurs, com.Name Kompetenze, s.Date Datum
    FROM Stamp s, User u1, User u2, Course c, Competence com
    WHERE s.Receiver = u1.Id
    AND s.Issuer = u2.Id
    AND s.Course = c.Id
    AND s.Competence = com.Id';

// reqeust
$query = 
    'SELECT r.Id, c.Name Kurs, u.Name Schüler
    FROM Request r, Course c, User u
    WHERE r.Course = c.Id
    AND r.Student = u.Id';

# teacher
// main page
$query =
    'SELECT Id, Name, Password Passwort, Role Rolle, Unlocked Freigeschaltet
    FROM User u
    WHERE u.Id = :userid';

// students table
$query =
    'SELECT s.Id, s.Name Schüler, c.Name Lehrer
    FROM User t, User s, Course c, Student_Course sc
    WHERE t.Id = :teacherId
    AND c.Teacher = t.Id
    AND sc.Course = c.Id
    AND sc.Student = s.Id';

// course table
$query =
    'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach
    FROM Course c, User t
    WHERE t.Id = :teacherId
    AND c.Teacher = t.Id';

// request table
$query = 
    'SELECT r.Id, s.Name Schüler, c.Name Kurs
    FROM User t, User s, Course c, Request r
    WHERE t.Id = :teacherId
    AND c.Teacher = t.Id
    AND r.Course = c.Id
    AND r.Student = s.Id';

# student
// main page
$query =
    'SELECT Id, Name, Password Passwort, Role Rolle, Unlocked Freigeschaltet
    FROM User u
    WHERE u.Id = :studentId';

// course table
$query =
    'SELECT c.Id, c.Name, c.Class Stufe, c.Subject Fach, t.Name Lehrer
    FROM User s, User t, Student_Course sc, Course c
    WHERE s.Id = :studentId
    AND sc.Student = s.Id
    AND sc.Course = c.Id
    AND t.Id = c.Teacher';

// stamps table
$query =
    'SELECT s.Id, r.Name Schüler, i.Name Lehrer, c.Name Kurs, com.Name Kompetenze, s.Date Datum
    FROM Stamp s, User r, User i, Course c, Competence com
    WHERE r.Id = :studentId
    AND s.Receiver = r.Id
    AND s.Issuer = i.Id
    AND s.Course = c.Id
    AND s.Competence = com.Id';
?>