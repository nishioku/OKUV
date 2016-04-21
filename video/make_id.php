<?php
require('password.php');

mysql_connect('localhost', 'root', '') or die(mysql_error());
mysql_select_db('okuvideo');
mysql_query('set names UTF-8');

session_start();
$U_se = session_id();

if (isset($_POST['make'])) {

  $id = mysql_real_escape_string($_POST['id']);
  $password = mysql_real_escape_string($_POST['password']);
  $password2 = mysql_real_escape_string($_POST['password2']);

  if (!empty($_FILES['userfile']['name'])) {
    $ic = $_FILES['userfile'];
  } else {
    $ic['name'] = './okuvideo.ico';
  }
  $ic_type = substr($ic['name'], -4);

  if ($ic_type != '.jpg' && $ic_type != '.png' && $ic_type != '.ico' && $ic_type != '.JPG' && $ic_type != '.PNG' && $ic_type != '.ICO') {
    header('Location: make_id.php?error=2');
    exit();
  }

  if ($password != $password2) {
    header('Location: make_id.php?error=0');
    exit();
  } else if (strlen($password) <= 4) {
    header('Location: make_id.php?error=1');
    exit();
  }

  if (isset($id) && isset($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql1 = sprintf('select * from user where name = "%s"', $id);
      $recordSet1 = mysql_query($sql1) or die(mysql_error());
      $check = mysql_fetch_assoc($recordSet1);

    if (!isset($check['id'])) {
      $sql2 = 'select count(*) as count from user';
        $recordSet2 = mysql_query($sql2) or die(mysql_error());
        $table = mysql_fetch_assoc($recordSet2);
        $count = ceil($table['count']);
 
      $sql3 = sprintf('insert into user set id="%d", session="%s", name="%s", password="%s", created="%s",
                         age=19, grade=1, major="（未設定）", intro_text="よろしくお願いします。"',
                $count,
                session_id(),
                $id, 
                $password_hash,  
                date('Y-m-d H:i:s'));

      mysql_query($sql3) or die(mysql_error());

      $sql4 = sprintf('select * from user where name = "%s"', $id);
      $recordSet3 = mysql_query($sql4) or die(mysql_error());
      $user = mysql_fetch_assoc($recordSet3);
      if (isset($ic['tmp_name'])) {
        $iconfilePath = './pic/' . date('YmdHis') . $user['id'];
        move_uploaded_file($ic['tmp_name'], $iconfilePath);
      } else {
        $iconfilePath = $ic['name'];
      }
      $sql5 = sprintf('update user set picture = "%s" where name = "%s"', $iconfilePath, $id);
      mysql_query($sql5) or die(mysql_error());
    } else {
      header('Location: make_id.php?error=3');
      exit();
    }
    header('Location: login.php');
    exit();
  }
}

?>

<html>
<head>
  <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
  <link rel="stylesheet" href="style.css">
  <title>アカウント作成 -OKUV</title>
</head>
<h1><a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a></h1>
<body>
<div id="page">
<div id="make1">
  <form action="" method="post" autocomplete="on" enctype="multipart/form-data">
    <table class="login">
      <tr class="login_title">
        <th colspan="2">アカウントを作成する</th>
      </tr>

      <tr class="login_top">
        <td>
          <input type="text" placeholder="ID(1～16文字)" required="required" id="id" name="id" size=32 maxlength="16">
        </td>
      </tr>
      <tr class="login_column1">
        <td>
          <input type="password" placeholder="パスワード（5～16文字）" required="required" id="password" name="password" value=""  size=32 maxlength="16">
        </td>
      </tr>
      <tr class="login_column2">
        <td>
          <input type="password" placeholder="パスワード（確認）" required="required" id="password2" name="password2" value=""  size=32 maxlength="32">
        </td>
      </tr>
      <tr class="login_bottom">
        <th >
        <?php
          if (isset($_GET['error'])){
            if ($_GET['error'] == 0) {
        ?>
        <p>パスワードが一致していません。</p>
        <?php
            } else if ($_GET['error'] == 1) {
        ?>
        <p>パスワードは５文字以上でお願いします。</p>
        <?php
            } else if ($_GET['error'] == 2) {
        ?>
        <p>iconの形式は.jpg、.png、.icoのいずれかでお願いします。</p>
        <?php
            } else if ($_GET['error'] == 3) {
        ?>
        <p>そのアカウント情報ではご利用になれません。</p>
        <?php
            }
          }
        ?>
        <input type="submit" id="make" name="make" value="登録する" />
      </th></tr>
    </table>
  </form>
</div>
<div id="make2">
  <a href="login.php">アカウントをお持ちの方</a>
</div>
</div>
</body>
</html>
