<?php
require('dbconnect.php');
require('password.php');
session_start();

if (isset($_POST['make'])) {

  $id = mysql_real_escape_string($_POST['id']);
  $password = mysql_real_escape_string($_POST['password']);
  $password2 = mysql_real_escape_string($_POST['password2']);

  if ($password != $password2) {

    header('Location: make_id.php?error=0');
    exit();

  }

  if ($password == $password2 && strlen($password) <= 4) {

    header('Location: make_id.php?error=1');
    exit();

  }

  if (isset($id) && isset($password)) {

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql1 = sprintf('select * from user where name = "%s"', $id);
      $recordSet1 = mysql_query($sql1) or die(mysql_error());
      $check = mysql_fetch_assoc($recordSet1);

    if (!isset($check['id'])) {

      $ic = $_FILES['userfile'];
      $ic_type = substr($ic['name'], -4);
      if ($ic_type != '.jpg' && $ic_type != '.png' && $ic_type != '.ico') {
        header('Location: make_id.php?error=2');
        exit();
      }

    $sql2 = 'select count(*) as count from user';
      $recordSet2 = mysql_query($sql2) or die(mysql_error());
      $table = mysql_fetch_assoc($recordSet2);
      $count = ceil($table['count']);
 
    $sql3 = sprintf('insert into user set id="%d",session="%s", name="%s", password="%s", created="%s"',
                $count,
                session_id(),
                $id, 
                $password_hash,  
                date('Y-m-d H:i:s'));
      mysql_query($sql3) or die(mysql_error());

    $user = mysql_query($sql3) or die(mysql_error());

    $iconfilePath = './pic/' . date('YmdHis') . $user['id'];
    move_uploaded_file($ic['tmp_name'], $iconfilePath);
 
    $sql4 = sprintf('update user set picture = "%s" where name = "%s"', $iconfilePath, $id);
      mysql_query($sql4) or die(mysql_error());

    }

    header('Location: login.php');
    exit();

  } else {

    header('Location: make_id.php?error=3');
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
<h1></h1>
<h2><a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a></h2>
<body>
<div id="page">
<div id="make1">
  <form action="" method="post" autocomplete="on" enctype="multipart/form-data">
    <table>
      <tr>
        <th colspan="2">アカウントを作成する</th>
      </tr>

      <tr>
        <td>ID</td>
        <td>
          <input type="text" placeholder="必須" required="required" id="id" name="id" size="64" maxlength="32">
        </td>
      </tr>
      <tr>
        <td>PASS</td>
        <td>
          <input type="password" placeholder="必須" required="required" id="password" name="password" value=""  size="64" maxlength="32">
        </td>
      </tr>
      <tr>
        <td>PASS（確認）</td>
        <td>
          <input type="password" placeholder="必須" required="required" id="password2" name="password2" value=""  size="64" maxlength="32">
        </td>
      </tr>
      <tr>
        <td>icon</td>
        <td>
          <input type="file" id="userfile" name="userfile" size="50" required="required" />
        </td>
      </tr>
      <tr>
        <th colspan="2">
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
        <p>そのアカウント情報ではご利用にはなれません。</p>
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
