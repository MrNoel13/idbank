<?php
  session_start();
  unset($_SESSION['user_id']);
  unset($userid);
  unset($user);
  session_destroy();
  if(isset($_GET['r'])) {
    header('Location: ' . $home_url);
  } else {
    header('Location: https://'.$_SERVER['HTTP_HOST'].'/idbank/');
  }
?>
