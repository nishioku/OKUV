<?php

$page = htmlspecialchars($_GET['page']);
$tag = htmlspecialchars($_GET['tag']);
$order = htmlspecialchars($_POST['order']);

header('Location: tag_serch.php?page=' . $page . '&tag=' . $tag . '&order=' . $order);

?>
