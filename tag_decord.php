<?php

require('dbconnect.php');

if (isset($Val2['v_id'])) {
  $sql_str = 'select * from video where v_id = ' . $Val2['v_id'];
  $recordSet_str = mysql_query($sql_str) or die(mysql_error());
  $str = mysql_fetch_assoc($recordSet_str);

  $key = " ";
  $strlen = strlen($str['tag']);

  $i = 0;
  $start = 0;
  $len = 1;
  while ($test = substr($str['tag'], $start, $len)) {
    $check = substr($str['tag'], $start, $len);
    if (substr($check, -1) == $key) {
      $Tag[$i] = substr($str['tag'], $start, $len-1);
      $i++;
      $start += $len -　1;
      $len = 1;
    } else {
      $len++;
    }
    if (substr($str['tag'], $start, $len) == $key) {
      $start++;
    }
  }

}

?>
