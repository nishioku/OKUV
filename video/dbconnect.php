<?php

mysql_connect('localhost', 'root', '') or die(mysql_error());
mysql_select_db('okuvideo');
mysql_query('set names UTF-8');

session_start();
$U_se = session_id();

//データベースから情報を取得---
  //userの情報を取得---
  $Sql1 = 'select * from user where session = "' . $U_se . '"';
    $RecordSet1 = mysql_query($Sql1) or die(mysql_error());
    $Val1 = mysql_fetch_assoc($RecordSet1);
    //ログインチェック---
    if (!isset($Val1['id'])) {
      header("Location: login.php");
      exit();
    }
    //---

  //---

  //何かしらの動画を視ている場合---
  if (!empty($_GET['vid'])) {
    $V_id = $_GET['vid'];
    $V_id += 0;
    //videoの情報を取得---
    $Sql2 = 'select * from video where v_id = ' . $V_id;
      $RecordSet2 = mysql_query($Sql2) or die(mysql_error());
      $Val2 = mysql_fetch_assoc($RecordSet2);
    //---
    //userの情報を取得---
    $Sql3 = 'select * from comment where v_id = ' . $V_id . ' order by c_id desc';
      $RecordSet3 = mysql_query($Sql3) or die(mysql_error());
      $Val3 = mysql_fetch_assoc($RecordSet3);
    //---

  } else if (!empty($_GET['multicheck'])) {
    $V_id = $_GET['vid1'];
    $V_id += 0;
    //videoの情報を取得---
    $Sql2 = 'select * from video where v_id = ' . $V_id;
      $RecordSet2 = mysql_query($Sql2) or die(mysql_error());
      $Val2 = mysql_fetch_assoc($RecordSet2);
    //---
    //userの情報を取得---
    $Sql3 = 'select * from comment where v_id = ' . $V_id . ' order by c_id desc';
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
