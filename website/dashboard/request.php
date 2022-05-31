<?php
require_once "../stempelAppManager.php";

if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

$requests = getRequests()
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
                <?php foreach ($requests[0] as $key => $request) { ?>
                    <th><?= $key ?></th>
                <?php } ?>
            </tr>
            <?php foreach ($requests as $request) { ?>
                <tr>
                    <?php foreach ($request as $val) { ?>
                        <td><?= $val ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
