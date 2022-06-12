<?php
$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
?>

<div class="buttonlist">
    <ul class="buttonlist-ul flex">
        <li><a href='entity.php?id=<?= $selectedUser['Id'] ?>' class="button">Übersicht</a></li>
        <!---------------- ADMIN BUTTONLIST ---------------->
        <?php if (isUserAdmin($sessionUser)) { ?>
            <?php if (isUserTeacher($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Schüler</a></li>
            <?php } elseif (isUserAdmin($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Nutzer</a></li>
            <?php } elseif (isUserStudent($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Lehrer</a></li>
            <?php } ?>
            <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
            <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
            <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <!---------------- TEACHER BUTTONLIST ---------------->
        <?php } elseif (isUserTeacher($sessionUser)) { ?>
            <?php if (isSameUser($sessionUser, $selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Schüler</a></li>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } elseif (isUserStudent($selectedUser)) { ?>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } ?>
            <!---------------- STUDENT BUTTONLIST ---------------->
        <?php } elseif (isUserStudent($sessionUser)) { ?>
            <?php if (isSameUser($sessionUser, $selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Lehrer</a></li>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } elseif (isUserTeacher($selectedUser)) { ?>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>