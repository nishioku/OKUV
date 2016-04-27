<?php
mysql_connect('localhost', 'root', '') or die(mysql_error());
mysql_select_db('okuvideo');
mysql_query('set names UTF-8');

session_start();
$U_se = session_id();

//データベースから情報を取得---
  //userの情報を取得---
  $Sql1 = sprintf('select * from user where session = "%s"', $U_se);
    $RecordSet1 = mysql_query($Sql1) or die(mysql_error());
    $Val1 = mysql_fetch_assoc($RecordSet1);
  //---

  //何かしらの動画を視ている場合---
  if (isset($_GET['vid'])) {
    $V_id = mysql_real_escape_string($_GET['vid']);

    //videoの情報を取得---
    $Sql2 = sprintf('select * from video where v_id = "%s"', $V_id);
      $RecordSet2 = mysql_query($Sql2) or die(mysql_error());
      $Val2 = mysql_fetch_assoc($RecordSet2);
    //---
    //userの情報を取得---
    $Sql3 = sprintf('select * from comment where v_id = "%s"', $V_id);
      $RecordSet3 = mysql_query($Sql3) or die(mysql_error());
      $Val3 = mysql_fetch_assoc($RecordSet3);
    //---

  }
  //---

  //$Sql = sprintf('select * from  where  ');
  //$RecordSet = mysql_query($Sql) or die(mysql_error());
  //$Val = mysql_fetch_assoc($RecordSet);

//---

require('password.php');
if (isset($_POST['id'])) {

  $id = mysql_real_escape_string($_POST["id"]);
  $password = mysql_real_escape_string($_POST["pass"]);

  $sql1 = sprintf('select * from user where name="%s"', $id);
  $recordSet1 = mysql_query($sql1) or die(mysql_error());

  while ($table = mysql_fetch_assoc($recordSet1)) {

    $db_hashed_pwd = $table['password'];

    if (password_verify($password, $db_hashed_pwd)) {

      $sql2 = sprintf('update user set session = "%s" where name = "%s"', session_id(), $id);
      $sql3 = sprintf('select count(*) as count from action_log');
      $sql4 = sprintf('select * from user where name = "%s"', $id);
        mysql_query($sql2) or die(mysql_error());

        $recordSet2 = mysql_query($sql3) or die(mysql_error());
        $recordSet3 = mysql_query($sql4) or die(mysql_error());

        $count = mysql_fetch_assoc($recordSet2);
        $u_id = mysql_fetch_assoc($recordSet3);

      $sql5 = sprintf('insert into action_log set
                id = "%d",
                u_id = "%d",
                session = "%s",
                type = 0,
                date = "%s"',
                $count['count'],
                $u_id['id'],
                session_id(),
                date('Y-m-d H:i:s'));
        mysql_query($sql5) or die(mysql_error());

      header("Location: index.php");
      exit();
    } else {
      header("Location: login.php?error=0");
      exit();
    }
  }
  header("Location: login.php?error=1");
  exit();
}
?>

<html>
<head>
  <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
  <link rel="stylesheet" href="style.css">
  <title>ログイン -OKUV</title>
</head>
<h1><a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a></h1>
<body>
<div id="page">
<div id="make1">
  <form action="" method="post" autocomplete="on" enctype="multipart/form-data">
    <table class="login">
      <tr class="login_title">
        <th>
          <p>ログイン</p>
        </th>
      </tr>

      <tr class="login_top">
        <td>
          <input type="text" placeholder="ID" required="required" name="id" id="id" size=32 maxlength="16">
        </td>
      </tr>
      <tr class="login_column1">
        <td>
          <input type="password" placeholder="パスワード" required="required" id="pass" name="pass" value=""  size=32 maxlength="16">
        </td>
      </tr>
      <tr class="login_bottom">
        <th>
          <?php
            if (isset($_GET['error'])){
          ?>
          <p>入力されたアカウント情報は無効です。</p>
          <?php
            }
          ?>
          <input type="submit" value="Go" style="width: 50px;">
        </th>
      </tr>
    </table>
  </form>
</div>
<div id="make2">
  <a href="make_id.php">アカウントをお持ちでない方</a>
</div>
</div>
</body>
</html>
