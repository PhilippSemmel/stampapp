<?php
require_once "../stempelAppManager.php";

function echoMessage($message)
{
    echo '<p id="commentaryregister"> ' . $message . '</p>';
    ?>
    <script>
        <!-- message element will be deleted after 10 seconds -->
        setTimeout(function () {
            document.getElementById('commentaryregister').remove();
        }, 10000);
    </script>
    <?php
}

function usernameExists(): bool
{
    return boolval(getUserByName($_POST['username_register'])) == True;
}

function passwordsMatch(): bool
{
    return $_POST["pw_register"] == $_POST["pw_register2"];
}

function setNameOfUserInSession($name)
{
    $_SESSION["name"] = $name;
}

function headToDashboard($userid)
{
    header("Location: ../dashboard/user/entity.php?id=" . $userid);
}

// register
if (isset($_POST["submit_register"])) {
    if (usernameExists()) {
        echoMessage('Username bereits vergeben');
        return;
    }
    if (!passwordsMatch()) {
        echoMessage('Passwörter stimmen nicht überein');
        return;
    }
    addNewUser($_POST['username_register'], $_POST['pw_register']);
    $user = getUserByName($_POST['username_register']);
    setNameOfUserInSession($user['Name']);
    headToDashboard($user['Id']);
}

// login
if (isset($_POST["submit_login"])) {
    $user = getUserByName($_POST['username_login']);
    if (password_verify($_POST["pw_login"], $user["Passwort"])) {                  // test if passwords match
        setNameOfUserInSession($user['Name']);
        headToDashboard($user['Id']);
    } else {
        echoMessage('Login fehlgeschlagen');
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="../css/login.css" rel="stylesheet">
</head>
<body>
<div class="login-register flex">
    <!---------------- LOGIN ---------------->
    <div id="login">
        <h1>ANMELDEN</h1>
        <form action="login.php" method="post">
            <label>
                <input type="text" name="username_login" placeholder="Name, Vorname" required class="typein"><br>
            </label>
            <label>
                <input type="password" name="pw_login" placeholder="Passwort" required class="typein"><br>
            </label>
            <button type="submit" name="submit_login" class="login-btn">Einloggen</button>
        </form>
    </div>
    <!---------------- REGISTER ---------------->
    <div id="register">
        <h1 class="h1">KUNDENKONTO ANLEGEN</h1>
        <form action="login.php" method="post">
            <label>
                <input type="text" name="username_register" placeholder="Name, Vorname" required class="typein"><br>
            </label>
            <label>
                <input type="password" name="pw_register" placeholder="Passwort" required class="typein"><br>
            </label>
            <label>
                <input type="password" name="pw_register2" placeholder="Passwort wiederholen" required
                       class="typein"><br>
            </label>
            <button type="submit" name="submit_register" class="login-btn">Erstellen</button>
        </form>
    </div>
</div>
</body>
</html>
