<?php session_start(); ?>

<div style="width: 500px; margin: 0 auto; background: #fff;">

  <form method="post" action="">
    <div style="padding: 10px; background: #fff; width: 100%; margin: 0;">
      <input type="text" name="test" size=64>
      <input type="submit" name="Submit" value="送信">
    </div>
  </form>

  <div style="padding: 10px; background: #eee; width: 100%; margin: 0; margin-top: 10px;">
    <?php
    if (!empty($_GET['test'])) {
      echo "<p>" . $_GET['test'] . "</p>";
    }
    ?>
  </div>

</div>

