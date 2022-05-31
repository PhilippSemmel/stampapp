<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION["name"]);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/config.css">
    <title></title>
</head>
<body>
<?php include '../header.inc.php'; ?>
<div class="mainbody">
    <iframe name="mainframe" class="mainframe" src="entity.php?id=<?= $user['Id'] ?>"></iframe>
</div>
<!---------------- BUTTONLIST ---------------->
<div class="buttonlist">
    <ul>
        <li><a href='entity.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Übersicht</a></li>
        <?php if (getRank($user['Name']) == LEHRER) { ?>
            <li><a href='user.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Schüler</a></li>
        <?php } elseif (getRank($user['Name']) == ADMIN) { ?>
            <li><a href='user.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Nutzer</a></li>
        <?php } ?>
        <li><a href='course.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Kurse</a></li>
        <li><a href='stamp.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Stempel</a></li>
        <?php if (getRank($user['Name']) > NUTZER) { ?>
            <li><a href='request.php?id=<?= $user['Id'] ?>' class="button" target="mainframe">Anfragen</a></li>
        <?php } ?>
    </ul>
</div>
</body>
</html>
