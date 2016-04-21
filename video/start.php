<?php
require('dbconnect.php');

session_start();
$U_se = session_id();

//データベースから情報を取得---
  //userの情報を取得---
  $Sql1 = sprintf('select * from user where session = "%s"', $U_se);
    $RecordSet1 = mysql_query($Sql1) or die(mysql_error());
    $Val1 = mysql_fetch_assoc($RecordSet1);
    //ログインチェック---
    if (!isset($Val1)) {
      header("Location: login.php");
    }
    //---

  //---

  //何かしらの動画を視ている場合---
  if (isset($_GET['vid'])) {
    $V_id = mysql_real_escape_string($_GET['vid']);

    //videoの情報を取得---
    $Sql2 = sprintf('select * from video where v_id = "%d"', $V_id);
      $RecordSet2 = mysql_query($Sql2) or die(mysql_error());
      $Val2 = mysql_fetch_assoc($RecordSet2);
    //---
    //userの情報を取得---
    $Sql3 = sprintf('select * from comment where v_id = "%d"', $V_id);
      $RecordSet3 = mysql_query($Sql3) or die(mysql_error());
      $Val3 = mysql_fetch_assoc($RecordSet3);
    //---

  }
  //---

  //$Sql = sprintf('select * from  where  ');
  //$RecordSet = mysql_query($Sql) or die(mysql_error());
  //$Val = mysql_fetch_assoc($RecordSet);

//---


?>
