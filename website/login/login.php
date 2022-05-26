
<?php
require_once "../config.php";

$mysql = new PDO('sqlite:../StempelApp.db');
if(isset($_POST["submit_register"])){
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Name = :user");// Username überprüfen
    $stmt->bindParam(":user", $_POST["username_register"]);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count == 0){                                                        // Username ist frei
        if($_POST["pw_register"] == $_POST["pw_register2"]){                // Passwörter stimmen übereinander ein
        $stmt = $mysql->prepare("INSERT INTO User (id, Name, Password, Role) VALUES (null, :user, :pw, 0)");

        $stmt->bindParam(":user", $_POST["username_register"]);
        $hash = password_hash($_POST["pw_register"], PASSWORD_BCRYPT);
        $stmt->bindParam(":pw", $hash);
        $stmt->execute();                                                 // Nutzer mit den Daten wird erstellt
        echo '<p id="commentaryregister"> Kundenkonto angelegt</p>';
        ?>
        <script>
        setTimeout(function(){document.getElementById('commentaryregister').remove();},10000); // Element wird nach 10000 Millisekunden gelöscht
        </script>
        <?php
        } else {
        echo '<p id="commentaryregister">Passwörter stimmen nicht übereinander ein</p>';
        ?>
        <script>
        setTimeout(function(){document.getElementById('commentaryregister').remove();},10000);
        </script>
        <?php
        }
    } else {
    echo '<p id="commentaryregister">Username bereits vergeben </p>';
    ?>
    <script>
    setTimeout(function(){document.getElementById('commentaryregister').remove();},10000);
    </script>
    <?php
    }
}
if(isset($_POST["submit_login"])){
    $stmt = $mysql->prepare("SELECT * FROM User WHERE Name = :user"); // Username überprüfen
    $stmt->bindParam(":user", $_POST["username_login"]);
    $stmt->execute();                                                   // Username existiert
    $row = $stmt->fetch();
    if(password_verify($_POST["pw_login"], $row["Password"])){
        $_SESSION["name"] = $row["Name"];
        header("Location: ../dashboard/index.php?id=".$row["Id"]);
    } else {
        echo '<p id="commentarylogin"> Login fehlgeschlagen</p>';
        ?>
        <script>
        setTimeout(function(){document.getElementById('commentarylogin').remove();},10000);
        </script>
        <?php
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
            <div id="login">
                <h1>ANMELDEN</h1>
                <form action="login.php" method="post">
                    <input type="text" name="username_login" placeholder="Name, Vorname" required class="typein"><br>
                    <input type="password" name="pw_login" placeholder="Passwort" required class="typein"><br>
                    <button type="submit" name="submit_login" class="login-btn">Einloggen</button>
                </form>
            </div>
            <div id="register">
                <h1 class="h1">KUNDENKONTO ANLEGEN</h1>
                <form action="login.php" method="post">
                    <input type="text" name="username_register" placeholder="Name, Vorname" required class="typein"><br>
                    <input type="password" name="pw_register" placeholder="Passwort" required class="typein"><br>
                    <input type="password" name="pw_register2" placeholder="Passwort wiederholen" required class="typein"><br>
                    <button type="submit" name="submit_register" class="login-btn">Erstellen</button>
                </form>
            </div>
        </div>
    </body>
</html>
