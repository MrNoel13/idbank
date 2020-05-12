<?php
  error_reporting(~0); ini_set('display_errors', 1);
  if($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <?php $title = "SecureLogin"; include("meta.php"); ?>
  </head>

  <body>
    <?php
      if(isset($_POST['submit'])){

        if(strlen($_POST['username']) <= 16 && strlen($_POST['username']) >= 3 && strlen($_POST['password']) <= 64 && strlen($_POST['password']) >= 8) {} else { // If the Site has been tampered with
          if(strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0) {
            die('<img src="/idbank/img/stopit.png">');
          } else {
            echo '<span id="notification">Bitte gib einen Benuternamen und ein Passwort an.</span>';
            $fail = true;
          }
        }

        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_database);

        if ($conn->connect_error) {
          echo '<span id="notification">Konnte keine Verbindung zur Datenbank aufbauen: '.$conn->connect_error.'</span>';
          $fail = true;
        }

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $rawrefresult = mysqli_query($conn, "SELECT * FROM users WHERE username = '".$username."'");

        $refresult = $rawrefresult->fetch_array(MYSQLI_ASSOC);

        if($refresult) {
         echo '<span id="notification">Dieser Benutzername ist bereits vergeben.</span>';
         $fail = true;
        }

        if(!isset($fail)) {
          $result = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('".$username."', '".$password."')");

          if ($result) {
            echo('<span id="notification" style="background-color: green;">Registrierung Erfolgreich! Jetzt <a href="login">anmelden</a>.</span>');
          } else {
            echo('<span id="notification">Ein unbekannter Fehler ist aufgetreten. Bitte versuche es Später erneut.</span>');
          }
        }

        mysqli_close($conn);
      }
    ?>

    <div class="popup">
      <h1>Registrieren</h1>

      <form action="" method="post">
        <input name="username" type="text" minlength="3" maxlength="16" placeholder="Minecraft-Name">
        <input name="password" type="password" minlength="8" maxlength="64" placeholder="Passwort">
        <input id="button" name="submit" type="submit" value="Registrieren">
      </form>
    </div>

  </body>
</html>
