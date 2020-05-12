<div class="header">
  <a href="."><img id="entry" src="/img/logo.png" width="35px" height="40px"></img></a>
  <a href="thread" id="entry">Forum-Thread</a>
  <a href="rules" id="entry">Regeln</a>
  <?php
    if(isset($userid)) {
      echo '<a href="account" id="entry">Mein Konto</a>';
      echo '<a href="history" id="entry">Kontoauszug</a>';
      echo '<a href="transaction" id="entry">Geld überweisen</a>';
      echo '<a href="securelogin/logout" id="entry">Abmelden</a>';
    } else {
      echo '<a href="securelogin/login?s=https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'" id="entry">Login</a>';
      echo '<a href="securelogin/register" id="entry">Registrieren</a>';
    }
  ?>
  <?php
    if(isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode']=='true') {
      echo '<a href="darkmode?a=false&b=https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" id="entry">Light Mode</a>';
    } else {
      echo '<a href="darkmode?a=true&b=https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" id="entry">Dark Mode</a>';
    }
  ?>
</div>
