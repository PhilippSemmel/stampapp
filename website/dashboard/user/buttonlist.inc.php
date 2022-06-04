<?php
$sessionUser = getUserByName($_SESSION['name']);
$selectedUser = getUserById($_GET['id']);
?>


<div class="buttonlist">
    <ul>
        <li><a href='entity.php?id=<?= $selectedUser['Id'] ?>' class="button">Übersicht</a></li>
        <!---------------- ADMIN BUTTONLIST ---------------->
        <?php if (userIsAdmin($sessionUser)) { ?>
            <?php if (userIsTeacher($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Schüler</a></li>
            <?php } elseif (userIsAdmin($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Nutzer</a></li>
            <?php } elseif (userIsStudent($selectedUser)) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Lehrer</a></li>
            <?php } ?>
            <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
            <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
            <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <!---------------- TEACHER BUTTONLIST ---------------->
        <?php } elseif (userIsTeacher($sessionUser)) { ?>
            <?php if ($sessionUser['Id'] == $selectedUser['Id']) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Schüler</a></li>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } elseif (userIsStudent($selectedUser)) { ?>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } ?>
            <!---------------- STUDENT BUTTONLIST ---------------->
        <?php } elseif (userIsStudent($sessionUser)) { ?>
            <?php if ($sessionUser['Id'] == $selectedUser['Id']) { ?>
                <li><a href='user.php?id=<?= $selectedUser['Id'] ?>' class="button">Lehrer</a></li>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } elseif (userIsTeacher($selectedUser)) { ?>
                <li><a href='course.php?id=<?= $selectedUser['Id'] ?>' class="button">Kurse</a></li>
                <li><a href='stamp.php?id=<?= $selectedUser['Id'] ?>' class="button">Stempel</a></li>
                <li><a href='request.php?id=<?= $selectedUser['Id'] ?>' class="button">Anfragen</a></li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>