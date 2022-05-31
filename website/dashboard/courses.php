<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$user = getUserByName($_SESSION['name']);
$courses = getCourses($user);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<main>
    <div id="content">
        <table>
            <tr>
                <?php foreach ($courses[0] as $key => $course) { ?>
                    <th><?= $key ?></th>
                <?php } ?>
            </tr>
            <?php foreach ($courses as $course) { ?>
                <tr>
                    <?php foreach ($course as $val) { ?>
                        <td><?= $val ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
