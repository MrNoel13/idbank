<?php
  $darkmode = $_GET['a'];
  $ref = $_GET['b'];

  if(isset($darkmode) && $darkmode=='true') {
    setcookie( "dark_mode", 'true', strtotime('+3 months'));
  } else {
    setcookie("dark_mode", 'false', strtotime('+3 months'));
  }

  header("Location: ".$ref);
 ?>
