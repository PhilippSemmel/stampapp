<?php
require_once "../dashboard.php";

$html = new EntityPage();

function print_entity_values()
{
    $sessionUser = getUserByName($_SESSION['name']);
    $selectedStamp = getStampById($_GET['id']); ?>
    <?php if (isUserAdmin($sessionUser)) { ?>
        <tr>
            <td><b>Id</b></td>
            <td><?= $selectedStamp['Id'] ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td><b>Text</b></td>
        <td><?= $selectedStamp['Text'] ?></td>
    </tr>
    <tr>
        <td><b>Empfänger</b></td>
        <td><a href="../user/entity.php?id=<?= $selectedStamp['Empfänger'] ?>"><?= getUserNameById($selectedStamp['Empfänger']) ?></a></td>
    </tr>
    <tr>
        <td><b>Aussteller</b></td>
        <td><a href="../user/entity.php?id=<?= $selectedStamp['Aussteller'] ?>"><?= getUserNameById($selectedStamp['Aussteller']) ?></a></td>
    </tr>
    <tr>
        <td><b>Kurs</b></td>
        <td><a href="../course/entity.php?id=<?= $selectedStamp['Kurs'] ?>"><?= getCourseNameById($selectedStamp['Kurs']) ?></a></td>
    </tr>
    <tr>
        <td><b>Kompetenz</b></td>
        <td><a href="../../competence.php?id=<?= $selectedStamp['Kompetenz'] ?>"><?= getCompetenceNameById($selectedStamp['Kompetenz']) ?></a></td>
    </tr>
    <tr>
        <td><b>Datum</b></td>
        <td><?= $selectedStamp['Datum'] ?></td>
    </tr>
<?php }

$html->print_html();
