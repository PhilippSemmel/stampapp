<?php
require_once "../dashboard.php";

$page = new EntityPage();

function print_entity_values()
{
    $sessionUser = getUserByName($_SESSION['name']);
    $selectedUser = getUserById($_GET['id']); ?>
    <?php if (isUserAdmin($sessionUser)) { ?>
        <tr>
            <td><b>Id</b></td>
            <td><?= $selectedUser['Id'] ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td><b>Name</b></td>
        <td><?= $selectedUser['Name'] ?></td>
    </tr>
    <tr>
        <td><b>Rolle</b></td>
        <td><?= ROLLEN_NAMEN[$selectedUser['Rolle']] ?></td>
    </tr>
    <!-- stats -->
    <?php if (isUserTeacher($selectedUser)) { ?>
        <tr>
            <td><b>Freigeschaltet</b></td>
            <td><?= getUnlockedText($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Schüler</b></td>
            <td><?= getUsersCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Kurse</b></td>
            <td><?= getCoursesCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Vergebene Stempel</b></td>
            <td><?= getStampsCount($selectedUser) ?></td>
        </tr>
    <?php } elseif (isUserStudent($selectedUser)) { ?>
        <tr>
            <td><b>Anzahl Lehrer</b></td>
            <td><?= getUsersCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Kurse</b></td>
            <td><?= getCoursesCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Stempel</b></td>
            <td><?= getStampsCount($selectedUser) ?></td>
        </tr>
    <?php } elseif (isUserAdmin($sessionUser)) { ?>
        <tr>
            <td><b>Anzahl Nutzer</b></td>
            <td><?= getUsersCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Lehrer</b></td>
            <td><?= getTeacherCount() ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Schüler</b></td>
            <td><?= getStudentCount() ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Kurse</b></td>
            <td><?= getCoursesCount($selectedUser) ?></td>
        </tr>
        <tr>
            <td><b>Durchschnittliche Schüler pro Kurs</b></td>
            <td><?= getAverageUsersPerCourse() ?></td>
        </tr>
        <tr>
            <td><b>Durchschnittliche Stempel pro Kurs</b></td>
            <td><?= getAverageStampsPerCourse() ?></td>
        </tr>
        <tr>
            <td><b>Anzahl Stempel</b></td>
            <td><?= getStampsCount($selectedUser) ?></td>
        </tr>
    <?php } ?>
<?php }

$page->print_html();
