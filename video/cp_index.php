<?php
require('dbconnect.php');

/*
  session更新
*/
$oldSession = session_id();
session_regenerate_id();
$newSession = session_id();

$sqlSession = sprintf('update user set session = "%s" where session = "%s"', $newSession, $oldSession);
mysql_query($sqlSession) or die(mysql_error());


/*
  各種変数を設定
*/
//キーワード検索
if (isset($_POST['serchKeyword'])) {
  $serchKeyword = htmlspecialchars($_POST['serchKeyword']);
}
//タグ検索
if (isset($_POST['serchTag'])) {
  $serchTag = htmlspecialchars($_POST['serchTag']);
}
//表示順
if (isset($_POST['order'])) {
  $order = htmlspecialchars($_POST['order']);
} else {
  $order = 0;
}
//ページ情報
if (isset($_GET['page'])) {
  $currentPage = htmlspecialchars($_GET['page']);
} else {
  header('Location: index.php?page=1');
  exit();
}


/*
  動画情報取得  
*/
$sqlVideoget = 'select * from video';
  /*
    キーワード検索
  */
  if (isset($serchKeyword)) {
    $sqlSerchKeyword = ' where title like '%" . $serchKeyword . "%'';
    $sqlVideoget = $sqlVideoget . $sqlSerchKeyword;
    $sqlSKCount = 'select count(*) as count from video where title regexp \'^(' . $serchKeyword . ')\'';
    $setSKCount = mysql_query($sqlSKCount) or die(mysql_error());
    $valSKCount = mysql_fetch_assoc($setSKCount);
  }
  /*
    タグ検索
  */
  if (isset($serchTag)) {
    $sqlTag = ' where tag like '%" . $serchTag . "%'';
    $sqlVideoget = $sqlVideoget . $sqlTag;
    $sqlSTCount = 'select count(*) as count from video where tag regexp \'^(' . $serchTag . ')\'';
    $setSTCount = mysql_query($sqlSTCount) or die(mysql_error());
    $valSTCount = mysql_fetch_assoc($setSTCount);
  }
  /*
    表示順
  */
  if ($order == 0) {
    $sqlOrder = ' order by created desc';
  } else if ($order == 1) {
    $sqlOrder = ' order by created asc';
  } else if ($order == 2) {
    $sqlOrder = ' order by times_p desc';
  } else if ($order == 3) {
    $sqlOrder = ' order by times_p asc';
  } else if ($order == 4) {
    $sqlOrder = ' order by times_c desc';
  } else if ($order == 5) {
    $sqlOrder = ' order by times_c asc';
  }
  $sqlVideoget = $sqlVideoget . $sqlOrder;

  /*
    ページ情報設定
  */
  if (isset($serchKeyword)) {
    $videoCount = $valSKcount['count'];
  } else if (isset($serchTag)) {
    $videoCount = $valSTcount['count'];
  } else {
    $sqlCountget = 'select count(*) as count from video';
    $setCountget = mysql_query($sqlCountget) or die(mysql_error());
    $valCountget = mysql_fetch_assoc($setCountget);
    $videoCount = $valCountget['count'];
  }

  $maxCount = 24;
   if ($videoCount < $maxCount) {
    $maxCount = $videoCount;
   }

  $maxPage = ceil($videoCount / $maxCount);
  $currentPage = max($currentPage, 1);
  $currentPage = min($currentPage, $maxPage);
  $startCount = ($currentPage - 1) * $maxCount;

  $sqlVideoget = $sqlVideoget . " limit " . $startCount . "," . $maxCount;
  $setVideoget = mysql_query($sqlVideoget) or die(mysql_error());
?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="icon" type="image/vnd.microsoft.icon" href="okuvlogo.ico">
    <link rel="stylesheet" href="style.css">
    <title>OKUV-ダイキョードーガ-</title>
  </head>

  <body>
  <div id="header1">
    <a href="index.php"><p class="head"><img src=okuvlogo.png width="80px" height="24px"></p></a>
    <ul>
      <li><p><a href="logout.php">ログアウト</a></p></li>
      <li><p><a href="post.php">投稿する</a></p></li>
      <li><p><a href="my_page.php"><?php echo $Val1['name']; ?></a></p></li>
      <li><p><img src="<?php echo $Val1['picture']; ?>" width="24px" height="24px"></p></li>
      <li>
        <form method="post" action="serch.php">
          <div id="serchHead">
            <input type="text" placeholder="動画を探す" required="required" name="serch" size=40 maxlength="32">
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
  <div id="main">
    <div id="header2">
      <p><a href="index.php"><img src="./okuvlogo.png"></a></p>
    </div>
    <div id="page">
      <?php if (isset($serchKeyword)) { ?>
        <div id="index_info">
          <p>キーワード「<?php echo $serchKeyword; ?>」を含む動画が<?php echo $valSKCount['count']; ?>件見つかりました。</p>
        </div>
      <?php } else if (isset($serchTag)) { ?>
        <div id="index_info">
          <p>タグ「<?php echo $serchTag; ?>」を含む動画が<?php echo $valSTCount['count']; ?>件見つかりました。</p>
        </div>
      <?php } ?>
      <div id="indexOrder">
          <form name="form" method="post" action="index.php?page=<?php echo $page; ?>">
            <select name="order" id="order">
              <?php
              $orderValue = array('新着順', '古い投稿順', '再生回数多い順', '再生回数少ない順', 'コメント数多い順', 'コメント数少ない順');
              $i = 0;
              while ($i <= 5) {
                echo '<option value="' . $i . '"';
                if ($i == $order) {
                  echo ' selected';
                }
                echo '>' . $orderValue[$i] . '</option>';
                $i++;
              }
              ?>
            </select>
            <input type="submit" name="submitOrder" value="並び替え">
          </form>
        </div>
        <div id="indexVideo">
        <?php
        while($valVideoget = mysql_fetch_assoc($setVideoget)){
        ?>
          <a href="player.php?vid=<?php echo $valVideoget['v_id']; ?>" >
            <table class="video">
              <tr class="video_top">
                <td class="video_top"><p>No.<?php echo $valVideoget['v_id']; ?></p></td>
              </tr>
              <tr class="video_column1">
                <td class="video_column1"><p><img src="<?php print('../outputTumb/' . $valVideoget['sam']); ?>" width=160 height=90 /></p></td>
              </tr>
              <tr class="video_column2">
                <td class="video_column2"><div><nobr><p style="overflow: hidden;"><?php echo $valVideoget['title']; ?></p></nobr></div></td>
              </tr>
              <tr class="video_column3">
                <td class="video_column3"><p>再生：<?php echo $valVideoget['times_p']; ?></p></td>
              </tr>
              <tr class="video_column4">
                <td class="video_column4"><p>コメント：<?php echo $valVideoget['times_c']; ?></p></td>
              </tr>
              <tr class="video_bottom">
                <td class="video_bottom"><p><?php echo $valVideoget['created']; ?></p></td>
              </tr>
            </table>
          </a>
        <?php
        }
        ?>
        </div>
        <div id="indexPage">
          <ul>
            <?php
            if ($currentPage > 1) {
            ?>
              <li><p class="indexBack"><a href="index.php?page=<?php print($currentPage - 1); ?>">前のページへ</a></p></li>
            <?php
            } else {
            ?>
              <li><p class="indexNone">前のページへ</p></li>
            <?php
            }
            if ($currentPage < $maxPage) {
            ?>
              <li><p class="indexNext"><a href="index.php?page=<?php print($currentPage + 1); ?>">次のページへ</a></p></li>
            <?php
            } else {
            ?>
              <li><p class="indexNone">次のページへ</p></li>
            <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </body>
</html>
