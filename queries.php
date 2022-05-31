<?php

# admin all info
// main page
$query =
    'SELECT Id, Name, Passwort, Rolle, Freigeschaltet
    FROM Nutzer n
    WHERE n.Id = :userid';

// user table
$query =
    'SELECT Id, Name, Passwort, Rolle, Freigeschaltet
    FROM Nutzer n
    ORDER BY n.Id';

// course table
$query =
    'SELECT c.Id, c.Name, c.Stufe, c.Fach, COUNT(c.Name) as "Schüler Anzahl", l.Name as Lehrer
    FROM Kurs c, Schüler_Kurs sc, Nutzer s, Nutzer l
    WHERE sc.Kurs = c.Id
    AND sc.Schüler = s.Id
    AND s.Rolle = 2
    AND c.Lehrer = l.Id
    GROUP BY c.Id';

// stamp table
$query =
    'SELECT s.Id, s.Text, s.Bild, u1.Name Schüler, u2.Name as Lehrer, c.Name as Kurs, com.Name Kompetenze, s.Datum
    FROM Stempel s, Nutzer u1, Nutzer u2, Kurs c, Kompetenz com
    WHERE s.Empfänger = u1.Id
    AND s.Aussteller = u2.Id
    AND s.Kurs = c.Id
    AND s.Kompetenz = com.Id';

// request
$query =
    'SELECT r.Id, c.Name as Kurs, n.Name as Schüler
    FROM Anfrage r, Kurs c, Nutzer n
    WHERE r.Kurs = c.Id
    AND r.Schüler = n.Id';

# teacher
// main page
$query =
    'SELECT Id, Name, Passwort, Rolle, Freigeschaltet
    FROM Nutzer n
    WHERE n.Id = :userid';

// students table
$query =
    'SELECT s.Id, s.Name as Schüler, c.Name as Lehrer
    FROM Nutzer l, Nutzer s, Kurs c, Schüler_Kurs sc
    WHERE l.Id = :teacherId
    AND c.Lehrer = l.Id
    AND sc.Kurs = c.Id
    AND sc.Schüler = s.Id';

// course table
$query =
    'SELECT c.Id, c.Name, c.Stufe, c.Fach
    FROM Kurs c, Nutzer l
    WHERE l.Id = :teacherId
    AND c.Lehrer = l.Id';

// request table
$query =
    'SELECT r.Id, s.Name as Schüler, c.Name as Kurs
    FROM Nutzer l, Nutzer s, Kurs c, Anfrage r
    WHERE l.Id = :teacherId
    AND c.Lehrer = l.Id
    AND r.Kurs = c.Id
    AND r.Schüler = s.Id';

# student
// main page
$query =
    'SELECT Id, Name, Passwort, Rolle, Freigeschaltet
    FROM Nutzer n
    WHERE n.Id = :studentId';

// course table
$query =
    'SELECT c.Id, c.Name, c.Stufe, c.Fach, l.Name as Lehrer
    FROM Nutzer s, Nutzer l, Schüler_Kurs sc, Kurs c
    WHERE s.Id = :studentId
    AND sc.Schüler = s.Id
    AND sc.Kurs = c.Id
    AND l.Id = c.Lehrer';

// stamps table
$query =
    'SELECT s.Id, r.Name Schüler, i.Name as Lehrer, c.Name as Kurs, com.Name Kompetenze, s.Datum
    FROM Stempel s, Nutzer r, Nutzer i, Kurs c, Kompetenz com
    WHERE r.Id = :studentId
    AND s.Empfänger = r.Id
    AND s.Aussteller = i.Id
    AND s.Kurs = c.Id
    AND s.Kompetenz = com.Id';