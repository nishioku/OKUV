<?php
require('dbconnect.php');

$page = htmlspecialchars($_GET['page']);
$tag = htmlspecialchars($_GET['tag']);
if (isset($_GET['order'])) {
  $order = htmlspecialchars($_GET['order']);
} else {
  $order = 0;
}

$sql_ts1 = "select * from video where tag like '%" . $tag . "%'";
$sql_ts2 = "select count(*) as count from video where tag like '%" . $tag . "%'";
if ($order == 0) {
  $sql_ts1 = $sql_ts1 . " order by created desc";
} else if ($order == 1){
  $sql_ts1 = $sql_ts1 . " order by created asc";
} else if ($order == 2){
  $sql_ts1 = $sql_ts1 . " order by times_p desc";
} else if ($order == 3){
  $sql_ts1 = $sql_ts1 . " order by times_p asc";
} else if ($order == 4){
  $sql_ts1 = $sql_ts1 . " order by times_c desc";
} else if ($order == 5){
  $sql_ts1 = $sql_ts1 . " order by times_c asc";
}

$recordSet_ts2 = mysql_query($sql_ts2) or die(mysql_error());
$val_ts2 = mysql_fetch_assoc($recordSet_ts2);

if (isset($_REQUEST['page'])) {
  $page = htmlspecialchars($_REQUEST['page']);
  $page += 0;
//ページ情報がなければ取得---
} else {
  header('Location: tag_serch.php?page=1&tag=' . $tag);
  exit();
//---

}
//ページが1より少なければ1に設定---
$page = max($page, 1);
//---
//最大表示件数の設定---
$pages = 25;
//---
$maxpage = ceil($val_ts2['count'] / $pages);
$page = min($page, $maxpage);
$start = ($page - 1) * $pages;

$sql_ts1 = $sql_ts1 . " limit " . $start . "," . $pages;
$recordSet_ts1 = mysql_query($sql_ts1) or die(mysql_error());
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
  <link rel="stylesheet" href="style.css">
  <title>OKUV-ダイキョードーガ-</title>
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
    <div id="ts_main">

      <div id="ts_info">
        <p>タグ「<?php echo $tag; ?>」を含む動画が<?php echo $val_ts2['count']; ?>件見つかりました。
      </div>

      <div id="ts_order">
        <form name="form" method="post" action="tag_serch_order.php?page=
          <?php
          echo $page . '&tag=' . $tag;
          ?>">
          <select name="order">
            <?php
            $orders = array('新しい順', '古い順', '再生回数多い順', '再生回数少ない順', 'コメント数多い順', 'コメント数少ない順');
            $i = 0;
            while ($i <= 5) {
              echo '<option value="' . $i . '"';
              if (isset($order)) {
                $select = $order;
                if ($select == $i) {
                  echo ' selected';
                }
              }
              echo '>' . $orders[$i] . '</option>';
              $i++;
            }
            ?>
          </select>
          <input type="submit" name="ts_submit" value="並び替え">
        </form> 
      </div>

      <div id="ts_video">
          <?php

          while($val_ts1 = mysql_fetch_assoc($recordSet_ts1)){
          ?>
            <a href="player.php?vid=<?php echo $val_ts1['v_id']; ?>" >
              <table class="video">
                <tr class="video_top">
                  <td class="video_top"><p>No.<?php echo $val_ts1['v_id']; ?></p></td>
                </tr>
                <tr class="video_column1">
                  <td class="video_column1"><p><img src="<?php print('../outputTumb/' . $val_ts1['sam']); ?>" width=160 height=90 /></p></td>
                </tr>
                </tr>
                <tr class="video_column2">
                  <td class="video_column2"><nobr><p style="overflow: hidden;"><?php echo $val_ts1['title']; ?></p></nobr></td>
                </tr>
                <tr class="video_column3">
                  <td class="video_column3">
                    <p>再生：<?php echo $val_ts1['times_p']; ?></p>
                  </td>
                </tr>
                <tr class="video_column4">
                  <td class="video_column4">
                    <p>コメント：<?php echo $val_ts1['times_c']; ?></p>
                  </td>
                </tr>
                <tr class="video_bottom">
                  <td class="video_bottom"><p><?php echo $val_ts1['created']; ?></p></td>
                </tr>
              </table>
            </a>
          <?php
          }
          ?>
      </div>

      <div id="index1-3">
        <ul>
        <?php
        //前のページがある場合---
        if ($page > 1) {
          ?>
          <li class="index1-3">
            <a href="tag_serch.php?page=<?php print($page - 1); ?>&tag=<?php print($tag); ?>">
              <p class="in_back">前のページへ</p>
            </a>
          </li>
          <?php
          //---
          //前のページが無い場合---
          } else {
          ?>
            <li><p class="in_none">前のページへ</p></li>
          <?php
          }
          //---
          //次のページがある場合---
          if ($page < $maxpage) {
          ?>
            <li class="index1-3"><a href="tag_serch.php?page=<?php print($page + 1); ?>&tag=<?php print($tag); ?>"><p class="in_next">次のページへ</p></a></li>
          <?php
          //---
          //次のページが無い場合---
          } else {
          ?>
            <li><p class="in_none">次のページへ</p></li>
          <?php
          }
          //---
          ?>
        </ul>
      </div>

    </div>
  </div>
</body>
</html>
