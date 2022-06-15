<?php
require_once "../dashboard.php";

$html = new EntityPage();

function print_entity_values()
{
    $sessionUser = getUserByName($_SESSION['name']);
    $selectedCourse = getCourseById($_GET['id']); ?>
    <?php if (isUserAdmin($sessionUser)) { ?>
        <tr>
            <td><b>Id</b></td>
            <td><?= $selectedCourse['Id'] ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td><b>Name</b></td>
        <td><?= $selectedCourse['Name'] ?></td>
    </tr>
    <tr>
        <td><b>Stufe</b></td>
        <td><?= $selectedCourse['Stufe'] ?></td>
    </tr>
    <tr>
        <td><b>Fach</b></td>
        <td><?= $selectedCourse['Fach'] ?></td>
    </tr>
    <tr>
        <td><b>Lehrer</b></td>
        <td><a href="../user/entity.php?id=<?= $selectedCourse['Lehrer'] ?>"><?= getUserNameById($selectedCourse['Lehrer']) ?></a></td>
    </tr>
    <?php if (!isUserStudent($sessionUser)) { ?>
        <tr>
            <td><b>Anzahl Schüler</b></td>
            <td><?= getUsersCountForCourse($selectedCourse) ?></td>
        </tr>
    <?php } ?>
<?php }

$html->print_html();
