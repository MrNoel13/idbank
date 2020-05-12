<?php $protected = "true"; include("init.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php $title = "Kontoauszug"; include("meta.php"); ?>
  </head>

  <body>
    <?php include("header.php"); ?>
    <center><h1><?php echo $user['username']; ?>'s Kontoauszug</h1></center>
    <table id="history">
      <tr>
        <th>Datum</th>
        <th>Eigenes Konto</th>
        <th>Betrag</th>
        <th>Fremdes Konto</th>
        <th>Betreff</th>
      </tr>
      <?php
      $history = json_decode($user['transactions'], true);
      if($history['count'] > 0) {
        for ($i=1; $i <= $history['count']; $i++) {
          echo '<tr>';
          echo '<td>'.date('Y-m-d H:i', $history[$i]['time']).'</td>';
          echo '<td>'.$user['username'].'</td>';
          if($history[$i]['direction'] == 'out') {
            echo '<td>'.$history[$i]['amount'].' ➡</td>';
          } elseif($history[$i]['direction'] == 'in') {
            echo '<td>⬅ '.$history[$i]['amount'].'</td>';
          }
          echo '<td>'.urldecode($history[$i]['target']).'</td>';
          echo '<td>'.urldecode($history[$i]['subject']).'</td>';
          echo '</tr>';
        }
      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Noch keine Überweisungen</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
      }
      ?>
    </table>
    <center>
      <p>Seite 1</p>
      <a id="button" href="?page=1">Vorherige Seite</a> <a id="button" href="?page=2">Nächste Seite</a>
    </center>

  </body>
</html>
