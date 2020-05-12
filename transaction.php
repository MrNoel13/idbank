<?php $protected = "false"; include("init.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php $title = "Geld überweisen"; include("meta.php"); ?>
  </head>

  <body>
    <?php
      include("header.php");

      function cleanUsername($string) {
        $dict = array(
          //"I'm"      => "I am",
          // Add your own replacements here
        );
        return strtolower(
          preg_replace(
            array( '#[ ]+#', '#[^A-Za-z0-9_]+#' ),
            array( '_', '' ),
            // the full cleanString() can be downloaded from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
            cleanString(
              str_replace( // preg_replace can be used to support more complicated replacements
                array_keys($dict),
                array_values($dict),
                urldecode($string)
              )
            )
          )
        );
      }

      function cleanString($text) {
        $utf8 = array(
          '/[áàâãªä]/u'   =>   'a',
          '/[ÁÀÂÃÄ]/u'    =>   'A',
          '/[ÍÌÎÏ]/u'     =>   'I',
          '/[íìîï]/u'     =>   'i',
          '/[éèêë]/u'     =>   'e',
          '/[ÉÈÊË]/u'     =>   'E',
          '/[óòôõºö]/u'   =>   'o',
          '/[ÓÒÔÕÖ]/u'    =>   'O',
          '/[úùûü]/u'     =>   'u',
          '/[ÚÙÛÜ]/u'     =>   'U',
          '/ç/'           =>   'c',
          '/Ç/'           =>   'C',
          '/ñ/'           =>   'n',
          '/Ñ/'           =>   'N',
          '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
          '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
          '/[“”«»„]/u'    =>   ' ', // Double quote
          '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
      }

      if(isset($_POST['submit'])){

        $target = cleanUsername($_POST['target']);
        $amount = preg_replace('#[^0-9.,]+#', '', $_POST['amount']);
        $subject = cleanString($_POST['subject']);

        $targetl = strlen($target);
        $subjectl = strlen($subject);

        if($targetl <= 16 && $targetl >= 3 && $amount >= 1 && $amount <= $user['balance'] && strlen($subject) <= 32) {} else { // If the Site has been tampered with
          if($targetl > 0 && $amount > 0) {
            die('<img src="img/stopit.png">');
          } else {
            echo '<span id="notification">Bitte gib einen gültigen Empfänger und Betrag an.</span>';
            $fail = true;
          }
        }

        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_database);

        $history = json_decode($user['transactions'], true);
        $history['count']++;
        $history[$history['count']] = ["time" => time(), "direction" => "out", "target" => $target, "amount" => $amount, "subject" => mysqli_real_escape_string($conn, urlencode($_POST['subject']))];

        if ($conn->connect_error) {
          echo '<span id="notification">Konnte keine Verbindung zur Datenbank aufbauen: '.$conn->connect_error.'</span>';
          $fail = true;
        }

        $rawcheck = mysqli_query($conn, "SELECT * FROM users WHERE username = '".$target."'");
        $check = $rawcheck->fetch_array(MYSQLI_ASSOC);

        if(!isset($check)) {
          echo '<span id="notification">Dieser Benutzer hat noch kein Konto bei uns.</span>';
          $fail = true;
        }

        if(!isset($fail)) {
          $newbalance = $user['balance']-$amount;
          $result = mysqli_query($conn, "UPDATE `users` SET `balance`='".$newbalance."',`transactions`='".json_encode($history)."' WHERE `id` = '".$userid."'");
          $result2 = mysqli_query($conn, "UPDATE `users` SET `balance`=balance+'".$amount."',`transactions`='".json_encode($history)."' WHERE `username` = '".$target."'");

          if ($result && $result2) {
            $user['balance'] = $newbalance;
            echo('<span id="notification" style="background-color: green;">Transaktion Erfolgreich!</span>');
          } else {
            echo('<span id="notification">Ein unbekannter Fehler ist aufgetreten. Bitte versuche es Später erneut.</span>');
          }
        }
      }
     ?>

     <div class="popup">
       <h1>Geld überweisen</h1>

       <form action="" method="post">
         <input name="target" type="text" minlength="3" maxlength="16" placeholder="Empfänger">
         <input name="amount" type="number" min="1" max="<?php echo $user['balance']; ?>" placeholder="Betrag">
         <input name="subject" type="text" maxlength="32" placeholder="Betreff">
         <input id="button" style="color: #eae9e8;" name="submit" type="submit" value="Überweisen">
       </form>
     </div>

  </body>
</html>
