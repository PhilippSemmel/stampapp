<?php
# $_SESSION = array(id, name, password, role, unlocked)

# admin all info
// main page
$query =
    'SELECT *
    FROM User u
    WHERE u.name = $_SESSION["name"]'

// user table (count with array len)
$query =
    'SELECT *
    FROM User u
    ORDER BY u.Id'

// course table (count with array len)
$query = 
    'SELECT c.Id, c.Name, c.Class, c.Subject, COUNT(c.Name) as "Student Number", teacher.Name
    FROM Course c, User_Course uc, User s, User t
    WHERE uc.Course = c.Id
    AND uc.User = s.Id
    AND s.Role = 2
    AND c.Teacher = t.Id
    GROUP BY c.Id'

// stamp table (count with array len)
$query = 
    'SELECT s.Id, s.Text, s.Image, u1.Name, u2.Name, c.Name, com.Name, s.Date 
    FROM Stamp s, User u1, User u2, Course c, Competence com
    WHERE s.Receiver = u1.Id
    AND s.Issuer = u2.Id
    AND s.Course = c.Id
    AND s.Competence = com.Id'

// reqeust (count with array len)
$query = 
    'SELECT r.Id, c.Name, u.Name
    FROM Request r, Course c, User u
    WHERE r.Course = c.Id
    AND r.Student = u.Id'

# teacher
// main page
$query =
    'SELECT *
    FROM User u
    WHERE u.name = $_SESSION["name"]'

// students table
$query =
    'SELECT s.Id, s.Name, c.Name
    FROM User t, User s, Course c, User_Course uc
    WHERE t.name = $_SESSION["name"]
    AND c.Teacher = t.Id
    AND uc.Course = c.Id
    AND uc.User = s.Id
    AND s.Role = 2'

// course table
$query =
    'SELECT c.Id, c.Name, c.Class, c.Subject
    FROM Course c, User t
    WHERE t.Name = $_SESSION["name"]
    AND c.Teacher = t.Id'

// request table
$query = 
    'SELECT r.Id, s.Name as "Schüler", c.Name as "Kurs"
    FROM User t, User s, Course c, Request r
    WHERE t.name = $_SESSION["name"]
    AND c.Teacher = t.Id
    AND r.Course = c.Id
    AND r.User = s.Id'

# student
// course table
$query =
    'SELECT c.Id, c.Name, c.Class, c.Subject, t.Name as "Lehrer"
    FROM User s, User t, User_Course uc, Course c
    WHERE s.Name = $_SESSION["name"]
    AND uc.User = s.Id
    AND uc.Course = c.Id
    AND t.Id = c.Teacher'

// stamps table
?>