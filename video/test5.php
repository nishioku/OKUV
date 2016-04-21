<?php

session_start();

$_POST['test'] = "Hello,world!";
$_SESSION['test'] = "Hello world";

header('Location: test6.php');
exit();
?>
