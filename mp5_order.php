<?php
$order = htmlspecialchars($_POST['select']['order']);
header('Location: my_page.php?uid=' . $Val1['id'] . '&val=5&order=' . $order);
exit();
?>
