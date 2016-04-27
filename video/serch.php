<?php
$url = htmlspecialchars($_POST['serch']);
if ($_POST['s_type'] == 1) {
  header('Location: index.php?page=1&serch=' . $url);
  exit();
} else {
  header('Location: tag_serch.php?page=1&tag=' . $url);
  exit();
}
?>
