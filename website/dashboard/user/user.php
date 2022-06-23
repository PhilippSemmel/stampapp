<?php
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$entityNumber = getUsersCount($selectedUser);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$pageNumber = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($pageNumber - 1));

$users = getUsers($selectedUser, $startAtEntity);

$page = new EntityTable($users, basename(__FILE__), $pageNumber, $totalPages);

function printColumnNames()
{
    global $users, $sessionUser;
    if (isUserAdmin($sessionUser)) {
        foreach ($users[0] as $key => $user) { ?>
            <th><?= $key ?></th>
        <?php }
    } elseif (isUserTeacher($sessionUser)) { ?>
        <th>Schüler</th>
    <?php } else { ?>
        <th>Lehrer</th>
    <?php }
}

function printEntityRow($user)
{
    global $sessionUser, $selectedUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $user['Id'] ?></td>
            <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
            <td><?= ROLLEN_NAMEN[$user['Rolle']] ?></td>
            <?php if (isUserAdmin($selectedUser)) { ?>
                <td><?= getUnlockedText($user) ?></td>
            <?php } ?>
        <?php } else { ?>
            <td><a href="entity.php?id=<?= $user['Id'] ?>"><?= $user['Name'] ?></a></td>
        <?php } ?>
    </tr>
<?php }

$page->print_html();
