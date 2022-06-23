<?php
require_once "../dashboard.php";

$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);

$entityNumber = getStampsCount($selectedUser);
$totalPages = ceil($entityNumber / ENTITIES_PER_PAGE);
$pageNumber = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$startAtEntity = (int)(ENTITIES_PER_PAGE * ($pageNumber - 1));

$stamps = getStamps($selectedUser, $startAtEntity);

$page = new EntityTable($stamps, basename(__FILE__), $pageNumber, $totalPages);

function printColumnNames()
{
    global $stamps, $sessionUser;
    foreach ($stamps[0] as $key => $stamp) {
        if ($key == 'Id' && !isUserAdmin($sessionUser)) { continue; } ?>
        <th><?= $key ?></th>
    <?php }
}

function printEntityRow($stamp)
{
    global $sessionUser ?>
    <tr>
        <?php if (isUserAdmin($sessionUser)) { ?>
            <td><?= $stamp['Id'] ?></td>
        <?php } ?>
        <td><a href="../stamp/entity.php?id=<?= $stamp['Id'] ?>"><?= $stamp['Text'] ?></a></td>
        <td><a href="entity.php?id=<?= $stamp['Empfänger'] ?>"><?= getUserNameById($stamp['Empfänger']) ?></a></td>
        <td><a href="entity.php?id=<?= $stamp['Aussteller'] ?>"><?= getUserNameById($stamp['Aussteller']) ?></a></td>
        <td><a href="../course/entity.php?id=<?= $stamp['Kurs'] ?>"><?= getCourseNameById($stamp['Kurs']) ?></a></td>
        <td><a href="../../competence.php?id=<?= $stamp['Kompetenz'] ?>"><?= getCompetenceNameById($stamp['Kompetenz']) ?></a></td>
        <td><?= $stamp['Datum'] ?></td>
    </tr>
<?php }

$page->print_html();