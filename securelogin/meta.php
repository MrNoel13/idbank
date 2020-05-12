<meta charset="utf-8">
<title><?php echo $title; ?> - devnoel.de</title>
<?php
  if(isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] == 'true') {
    echo '<link rel="stylesheet" type="text/css" href="css/dark.css">';
  } else {
    echo '<link rel="stylesheet" type="text/css" href="css/light.css">';
  }
?>

<?php
  //IDBank Database
  $db_servername = "localhost";
  $db_database = "";
  $db_username = "";
  $db_password = "";
 ?>
