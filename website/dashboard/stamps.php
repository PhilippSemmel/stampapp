<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$stamps = getStamps()
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
                <?php foreach ($stamps[0] as $key => $stamp) { ?>
                    <th><?= $key ?></th>
                <?php } ?>
            </tr>
            <?php foreach ($stamps as $stamp) { ?>
                <tr>
                    <?php foreach ($stamp as $val) { ?>
                        <td><?= $val ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
