<?php
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$entityNumber = getCoursesCount($selectedUser);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$pageNumber = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($pageNumber - 1));

$courses = getCourses($selectedUser, $startAtEntity);

$page = new EntityTable($courses, basename(__FILE__), $pageNumber, $totalPages);

function printColumnNames()
{
    global $courses, $sessionUser;
    foreach ($courses[0] as $key => $course) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($course)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $course['Id'] ?></td>
        <?php } ?>
        <td><a href="../course/entity.php?id=<?= $course['Id'] ?>"><?= $course['Name'] ?></a></td>
        <td><?= $course['Stufe'] ?></td>
        <td><?= $course['Fach'] ?></td>
<!--        --><?php //if (!isUserTeacher($sessionUser) or !isSameUser($sessionUser, $selectedUser)) { ?>
            <td><a href='entity.php?id=<?= $course['Lehrer'] ?>'><?= getUserNameById($course['Lehrer']) ?></a></td>
<!--        --><?php //} ?>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <td><?= $course['Anzahl Schüler'] ?></td>
        <?php } ?>
    </tr>
<?php }

$page->print_html();