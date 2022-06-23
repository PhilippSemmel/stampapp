<?php
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedCourse = getUserById($_GET['id']);

$entityNumber = getUsersCountForCourse($selectedCourse);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$pageNumber = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($pageNumber - 1));

$users = getUsersForCourse($selectedCourse, $startAtEntity);

$page = new EntityTable($users, basename(__FILE__), $pageNumber, $totalPages);

function printColumnNames()
{
    global $users, $sessionUser;
    foreach ($users[0] as $key => $selectedCourse) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($user)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $user['Id'] ?></td>
        <?php } ?>
        <td><a href="../user/entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
    </tr>
<?php }

$page->print_html();