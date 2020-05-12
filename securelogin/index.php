<?php
  error_reporting(~0); ini_set('display_errors', 1);

  session_start();
  if(!isset($_SERVER["HTTPS"])) {
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

      if(isset($_GET['r'])){
        echo('<span id="notification">Du musst angemeldet sein, um diese Seite sehen zu können.</span>');
        $r = $_GET['r'];
        $redirect = "true";
      }
      if(isset($_GET['s'])){
        $r = $_GET['s'];
        $redirect = "true";
      }

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

        if(!isset($fail)) {
          $username = mysqli_real_escape_string($conn, $_POST['username']);
          $password = $_POST['password'];

          $rawresult = mysqli_query($conn, "SELECT * FROM users WHERE username = '".$username."'");

          $result = $rawresult->fetch_array(MYSQLI_ASSOC);

          if ($result) {
            if(password_verify($password, $result['password'])) {
              echo('<span id="notification" style="background-color: green;">Anmelden erfolgreich! Du wirst jetzt weitergeleitet..</span>');
              $_SESSION['user_id'] = $result['id'];
              if($redirect) {
                echo '<meta http-equiv="refresh" content="2;url='.$r.'" />';
              }
            } else {
              echo('<span id="notification">Benutzername oder Passwort ist falsch.</span>'); // Incorrect Password
            }

            mysqli_free_result($rawresult);
          } else {
            echo('<span id="notification">Benutzername oder Passwort ist falsch.</span>'); // Incorrect Username
          }
        }

        mysqli_close($conn);
      }
    ?>

    <div class="popup">
      <h1>Anmelden</h1>

      <form action="" method="post">
        <input name="username" type="text" minlength="3" maxlength="16" placeholder="Minecraft-Name">
        <input name="password" type="password" minlength="8" maxlength="64" placeholder="Passwort">
        <input id="button" name="submit" type="submit" value="Login">
      </form>
    </div>

  </body>
</html>
