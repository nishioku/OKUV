<?php
require('dbconnect.php');

$sql1 = 'select count(*) as cnt from video';
$sql_cal = 'select count(*) as cnt_al from action_log';
  $recordSet1 = mysql_query($sql1) or die(mysql_error());
  $recordSet_cal = mysql_query($sql_cal) or die(mysql_error());
  $val1 = mysql_fetch_assoc($recordSet1);
  $val_cal = mysql_fetch_assoc($recordSet_cal);

$count = ceil($val1['cnt']);
$tumb = substr($_SESSION['join']['video'], 0, -4) . ".jpg";

if ($_SESSION['join']['category'] == "") {
  $_SESSION['join']['category'] = "未設定";
}
if ($_SESSION['join']['tag'] == "") {
  $_SESSION['join']['tag'] = "タグ無し動画";
}

$sql4 = "select max(v_id) as max_vid from video";
$recordSet4 = mysql_query($sql4) or die(mysql_error());
$val4 = mysql_fetch_assoc($recordSet4);
$val4['max_vid']++;

if (!empty($_POST)) {
	//登録処理をする
	$sql2 = sprintf('insert into video set v_id="%d", multi=0, u_id="%d", title="%s", v_name="%s", original_name="%s", times_p=0, times_c=0, sam="%s", category="%s", tag="%s", created="%s",
		 intro_text="%s"',
	 	$val4['max_vid'],
                $Val1['id'],
		mysql_real_escape_string($_SESSION['join']['title']),
		mysql_real_escape_string($_SESSION['join']['video']),
                mysql_real_escape_string($_SESSION['join']['video1']),
		$tumb,
                mysql_real_escape_string($_SESSION['join']['category']),
                mysql_real_escape_string($_SESSION['join']['tag']),
		date('Y-m-d H:i:s'),
		mysql_real_escape_string($_SESSION['join']['intro_text'])
		);
        $sql3 = sprintf('insert into action_log set
                id = "%d",
                u_id = "%d",
                type = 4,
                date = "%s",
                v_id = "%d",
                session = "%s"',
                $val_cal['cnt_al'],
                $Val1['id'],
                date('Y-m-d H:i:s'),
                $count,
                $U_se);
        mysql_query($sql2) or die(mysql_error());
	mysql_query($sql3) or die(mysql_error());
	unset($_SESSION['join']);

	header('Location: thanks.php');
	exit();
}
?>

<html>
<head>
        <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
        <link rel="stylesheet" href="style.css">
        <title>投稿確認 -OKUV</title>
</head>
<body>
<div id="pl_head">
    <p class="pl_head"><a href="index.php"><img src=./okuvlogo.png width="80px" height="24px" /><a></p>
    <ul>
      <li><p><a href="logout.php">ログアウト</a></p></li>
      <li><p><a href="post.php">投稿する</a></p></li>
      <li><p><a href="my_page.php"><?php echo $Val1['name']; ?></a></p></li>
      <li><img src="<?php echo $Val1['picture']; ?>" width="24px" height="24px" /></li>
      <li>
        <form method="post" action="serch.php">
          <div id="pl_hserch">
            <input type="text" placeholder="動画を探す" required="required" name="serch" size="40px" maxlength="32">
            <select name="s_type">
              <option value="1">キーワード</option>
              <option value="2">タグ</option>
            </select>
            <input type="submit" namei="Submit" value="検索">
          </div>
        </form>
      </li>
    </ul>
  </div>
<div id="pl">
    <h2>
      <a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a>
    </h2>
<div id="page">
<div id="post">
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
          <table class="post">
            <tr class="title_po">
              <th colspan="2">動画情報の確認</th>
            </tr>
            <tr class="top_po">
              <td class="topl_po">タイトル</td>
              <td class="topr_po">
                <p><?php echo htmlspecialchars($_SESSION['join']['title'], ENT_QUOTES, 'UTF-8'); ?></p>
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">ファイル</td>
              <td class="columnr_po">
                <p><?php echo htmlspecialchars($_SESSION['join']['video1'], ENT_QUOTES, 'UTF-8'); ?></p>
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">サムネイル</td>
              <td class="columnr_po">
                <p><img src="<?php echo "../outputTumb/" . $tumb; ?>"></p>
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">カテゴリ</td>
              <td class="columnr_po">
                <p><?php echo htmlspecialchars($_SESSION['join']['category'], ENT_QUOTES, 'UTF-8'); ?></p>
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">タグ</td>
              <td class="columnr_po">
                <p><?php echo htmlspecialchars($_SESSION['join']['tag'], ENT_QUOTES, 'UTF-8'); ?></p>
              </td>
            </tr>
            <tr class="bottom_po">
              <td class="bottoml_po">説明文</td>
              <td class="bottomr_po">
                <p><?php echo htmlspecialchars($_SESSION['join']['intro_text'], ENT_QUOTES, 'UTF-8'); ?></p>
              </td>
            </tr>
            <tr class="submit_po">
              <th colspan="2"><p>
                <a href="post.php?action=rewrite">&laquo;&nbsp;投稿を取り消す</a> | <input type="submit" value="投稿を確定する" />
              </p></th>
            </tr>
          </table>
</form>
</div>
</div>
</div>
</body>
</html>
