<?php
$sessionUser = getUserByName($_SESSION['name']);
?>

<div class="buttonlist">
    <ul class="buttonlist-ul flex">
        <li><a href='entity.php?id=<?= $_GET['id'] ?>' class="button">Übersicht</a></li>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <li><a href='student.php?id=<?= $_GET['id'] ?>' class="button">Schüler</a></li>
        <?php } ?>
        <li><a href='stamp.php?id=<?= $_GET['id'] ?>' class="button">Stempel</a></li>
        <?php if (!isUserStudent($sessionUser)) { ?>
            <li><a href='request.php?id=<?= $_GET['id'] ?>' class="button">Anfragen</a></li>
        <?php } ?>
    </ul>
</div>