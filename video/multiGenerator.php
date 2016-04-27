<?php
require('dbconnect.php');
$vid = htmlspecialchars($_SESSION['vid']);
$uid = htmlspecialchars($_SESSION['uid']);
$vid = mb_convert_encoding($vid, 'UTF-8', 'auto');
$uid = mb_convert_encoding($uid, 'UTF-8', 'auto');
$vid = mb_convert_kana($vid, 'S', 'UTF-8');
$uid = mb_convert_kana($uid, 'S', 'UTF-8');

if (mb_substr($vid, -1, 1, 'UTF-8') != "　"){
  $vid = $vid . "　";
}
if (mb_substr($uid, -1, 1, 'UTF-8') != "　"){
  $uid = $uid . "　";
}

$i = 0;
$start = 0;
$len = 1;
$key = "　";
$key = mb_convert_encoding($key, 'UTF-8', 'auto');

while ($check = mb_substr($vid, $start, $len, 'UTF-8')) {
  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
  }

  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
    $len2 = $len - 1;
    $_SESSION['multiVid'][$i] = mb_substr($vid, $start, $len2, 'UTF-8');
    $i++;
    $start = $start + $len;
    $len = 1;

  } else if(mb_substr($check, $start, 1, 'UTF-8') == $key) {
    $start++;

  } else {
    $len++;

  }
}
$i = 0;
$start = 0;
$len = 1;
$check="";
while ($check = mb_substr($uid, $start, $len, 'UTF-8')) {
  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
  }

  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
    $len2 = $len - 1;
    $_SESSION['multiUid'][$i] = mb_substr($uid, $start, $len2, 'UTF-8');
    $i++;
    $start = $start + $len;
    $len = 1;

  } else if(mb_substr($check, $start, 1, 'UTF-8') == $key) {
    $start++;

  } else {
    $len++;

  }
}


?>
