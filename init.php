<?php
  error_reporting(~0); ini_set('display_errors', 1);

  //IDBank Database
  $db_servername = "localhost";
  $db_database = "";
  $db_username = "";
  $db_password = "";

  session_start();
  if(isset($_SESSION['user_id'])) {
    $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_database);

    if ($conn->connect_error) {
      die('<span id="notification">Konnte keine Verbindung zur Datenbank aufbauen: '.$conn->connect_error.'</span>');
    }

    $userid = $_SESSION['user_id'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = '".$userid."'");
    $user = $result->fetch_array(MYSQLI_ASSOC);
  }

  if(!isset($_SESSION['user_id']) && $protected=="true") {
    header("Location: https://".$_SERVER["HTTP_HOST"]."/idbank/securelogin/login?r=https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
  }

  if(!isset($_SERVER["HTTPS"])) {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
 ?>
