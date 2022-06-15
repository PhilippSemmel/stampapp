<?php
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getCourseById($_GET['id']);

$entityNumber = getRequestsCountForCourse($selectedCourse);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($page - 1));

$requests = getRequestsForCourse($selectedCourse, $startAtEntity);

$html = new EntityTable($requests, basename(__FILE__), $page, $totalPages);

function printColumnNames()
{
    global $requests, $sessionUser;
    if (sizeof($requests) != 0) {
        foreach ($requests[0] as $key => $request) {
            if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
            <th><?= $key ?></th>
        <?php }
    }
}

function printEntityRow($request)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $request['Id'] ?></td>
        <?php } ?>
        <?php if (!isUserTeacher($sessionUser)) { ?>
            <td><a href="../user/entity.php?id=<?= $request['Schüler'] ?>"><?= getUserNameById($request['Schüler']) ?></a></td>
        <?php } else { ?>
            <td><?= getUserNameById($request['Schüler']) ?></td>
        <?php } ?>
    </tr>
<?php }

$html->print_html();