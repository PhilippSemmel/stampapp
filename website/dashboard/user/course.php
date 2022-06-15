<?php
require_once "../../stempelAppManager.php";
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$entityNumber = getRequestCount($selectedUser);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($page - 1));

$courses = getCourses($selectedUser, $startAtEntity);

$html = new EntityTable($courses, basename(__FILE__), $page, $totalPages);

function printColumnNames()
{
    global $courses, $sessionUser;
    if (sizeof($courses) != 0) {
        foreach ($courses[0] as $key => $course) {
            if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
            <th><?= $key ?></th>
        <?php }
    }
}

function printEntityRow($course)
{
    global $sessionUser, $selectedUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $course['Id'] ?></td>
        <?php } ?>
        <td><a href="../course/entity.php?id=<?= $course['Id'] ?>"><?= $course['Name'] ?></a></td>
        <td><?= $course['Stufe'] ?></td>
        <td><?= $course['Fach'] ?></td>
        <?php if (!isUserTeacher($sessionUser)) { ?>
            <td><a href='entity.php?id=<?= $course['Lehrer'] ?>'><?= getUserNameById($course['Lehrer']) ?></a></td>
        <?php } ?>
        <?php if (!isUserStudent($selectedUser)) { ?>
            <td><?= $course['Anzahl Schüler'] ?></td>
        <?php } ?>
    </tr>
<?php }

$html->print_html();