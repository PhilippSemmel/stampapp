<?php
require_once "../../stempelAppManager.php";
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$entityNumber = getRequestCount($selectedUser);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($page - 1));

$requests = getRequests($selectedUser, $startAtEntity);

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
            <td><a href="entity.php?id=<?= $request['Schüler'] ?>"><?= getUserNameById($request['Schüler']) ?></a></td>
        <?php } else { ?>
            <td><?= getUserNameById($request['Schüler']) ?></td>
        <?php } ?>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <td><a href='../course/entity.php?id=<?= $request['Kurs'] ?>'><?= getCourseNameById($request['Kurs']) ?></a></td>
        <?php } else { ?>
            <td><?= getCourseNameById($request['Kurs']) ?></td>
        <?php } ?>
    </tr>
<?php }

$html->print_html();