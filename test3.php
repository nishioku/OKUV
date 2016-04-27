<?php

require('dbconnect.php');

$sqlVideoget = "select * from video";
$serchKeyword = "test";

$sqlSerchKeyword = ' where title like "%' . $serchKeyword . '%"';
$sqlVideoget = $sqlVideoget . $sqlSerchKeyword;
$sqlSKCount = 'select count(*) as count from video where title like "%' . $serchKeyword . '%"';
$setVideoget = mysql_query($sqlVideoget) or die(mysql_error());
$valVideoget = mysql_fetch_assoc($setVideoget);

var_dump($valVideoget);
?>
