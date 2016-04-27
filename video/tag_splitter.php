<?php
require('dbconnect.php');
$str = htmlspecialchars($Val2['tag']);
$str = mb_convert_encoding($str, 'UTF-8', 'auto');
$str = mb_convert_kana($str, 'S', 'UTF-8');

if (mb_substr($str, -1, 1, 'UTF-8') != "　"){
  $str = $str . "　";
}

$i = 0;
$start = 0;
$len = 1;
$key = "　";
$key = mb_convert_encoding($key, 'UTF-8', 'auto');

while ($check = mb_substr($str, $start, $len, 'UTF-8')) {
  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
  }

  if (mb_substr($check, -1, 1, 'UTF-8') == $key) {
    $len2 = $len - 1;
    $Val4[$i] = mb_substr($str, $start, $len2, 'UTF-8');
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
