<?php
require('dbconnect.php');
session_start();

//login状況の確認---
  $sql1 = sprintf('select id as id from user where session = "%s"', session_id());
  $result = mysql_query($sql1) or die(mysql_error());
  $result_val = mysql_fetch_assoc($result);

  if (!isset($result_val['id'])) {
    header("Location: login.php");
    exit();
//---

//login状態を確認した後の動作---
  } else {
  //アカウント情報の取得---
    $sql2 = sprintf('select picture as pict from user where session="%s"', session_id());
    $recordSet1 = mysql_query($sql2) or die(mysql_error());
    $pict = mysql_fetch_assoc($recordSet1);

    $sql3 = sprintf('select count(*) as count from action_log');
    $sql4 = sprintf('select * from user where session = "%s"', session_id());
    $sql5 = sprintf('select * from video where v_id = "%s"', mysql_real_escape_string($_GET['vid']));

    $recordSet2 = mysql_query($sql3) or die(mysql_error());
    $recordSet3 = mysql_query($sql4) or die(mysql_error());
    $recordSet4 = mysql_query($sql5) or die(mysql_error());

      $count = mysql_fetch_assoc($recordSet2);
      $u_id = mysql_fetch_assoc($recordSet3);
      $v_id = mysql_fetch_assoc($recordSet4);
  //---

  //始めてみた動画か否か、同じセッションで見てるか否か---
    $sql6 = sprintf('select max(id) as id from action_log where u_id="%d" and v_id="%s"', $u_id['id'], $v_id['v_id']);
      $recordSet5 = mysql_query($sql6) or die(mysql_error());
      $last_video = mysql_fetch_assoc($recordSet5);
  //---

  //始めてみた動画じゃなかった場合---
    $sql8 = sprintf('update video set times_p = times_p + 1 where v_id="%s"', $v_id['v_id']);
    if (isset($last_video['id'])) {
  
      $sql7 = sprintf('select session from action_log where id="%d"', $last_video['id']);
        $recordSet6 = mysql_query($sql7) or die(mysql_error());
        $last_session = mysql_fetch_assoc($recordSet6);
    
      if ($last_session['session'] != $u_id['session']) {
        mysql_query($sql8) or die(mysql_error());
      }
        
    } else if (!isset($last_video['id'])) {
      mysql_query($sql8) or die(mysql_error());
    }

//action_log更新---
    $sql9 = sprintf('insert into action_log set
                id = "%d",
                u_id = "%d",
                type = 2,
                date_y = "%d",
                date_m = "%d",
                date_d = "%d",
                date_h = "%d",
                date_i = "%d",
                date_s = "%d",
                v_id = "%s",
                session = "%s"',
                $count['count'],
                $u_id['id'],
                idate('Y'),
                idate('m'),
                idate('d'),
                idate('H'),
                idate('i'),
                idate('s'),
                mysql_real_escape_string($_GET['vid']),
                session_id());
      mysql_query($sql9) or die(mysql_error());
  }
//---

$sql10 = sprintf('select v_name as v_name from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));
$sql11 = sprintf('select title as title from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));
//$sql12 = sprintf('update video set times_p=times_p+1 where v_id="%s"', mysql_real_escape_string($_GET['vid']));
$sql13 = sprintf('select times_p as timesplay from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));
$sql14 = sprintf('select sam as sam from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));
$sql15 = sprintf('select times_c as timescom from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));
$sql16 = sprintf('select count(*) as cnt from comment');
$sql17 = sprintf('select comment as comment, created as created from comment where v_id="%s" order by created desc', mysql_real_escape_string($_GET['vid']));
$sql18 = sprintf('select intro_text as intro_text from video where v_id="%s"', mysql_real_escape_string($_GET['vid']));

$v_name = mysql_query($sql10) or die(mysql_error());
$title = mysql_query($sql11) or die(mysql_error());
//$play_times = mysql_query($sql12) or die(mysql_error());
$timesp = mysql_query($sql13) or die(mysql_error());
$sam = mysql_query($sql14) or die(mysql_error());
$timescom = mysql_query($sql15) or die(mysql_error());
$recordSet7 = mysql_query($sql16) or die(mysql_error());
$recordSet8 = mysql_query($sql17) or die(mysql_error());
$intro_text = mysql_query($sql18) or die(mysql_error());

$name = mysql_fetch_assoc($v_name);
$tumb = mysql_fetch_assoc($sam);
$ti = mysql_fetch_assoc($title);
$tp = mysql_fetch_assoc($timesp);
$tc = mysql_fetch_assoc($timescom);
$table2 = mysql_fetch_assoc($recordSet7);
$count = ceil($table2['cnt']);
$it = mysql_fetch_assoc($intro_text);
?>
  
<HTML>
	<HEAD>
		<link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
		<link rel="stylesheet" href="style.css">
	<TITLE><?php echo $ti["title"]; ?> -OKUV</TITLE>
	</HEAD>
	<h1>
      <ul>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="post.php">投稿する</a></li>
        <li><a href="index.php">マイページ</a></li>
        <li style="width: 25px;height: 25px;"><img src="<?php echo $pict['pict']; ?>" width="25" height="25"></li>
        <li style="width: 350px;height: 25px;">
          <form method="post" action="serch.php">
            <div id="index_serch">
              <input type="text" placeholder="動画を探す" required="required" name="serch" size="40" maxlength="32">
              <input type="submit" namei="Submit" value="検索">
            </div>
          </form>
        </li>
      </ul>
    </h1>
    <h2>
      <a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a>
    </h2>	
	<BODY>
	<div id="page">
		<div id="p1">
			<h3><a href=<?php sprintf("player.php?vid=%s", mysql_real_escape_string($_GET['vid']))?>><?php echo $ti["title"]; ?></a></h3>
			<p><img src='../outputTumb/<?php echo $tumb["sam"]; ?>' /></p>
			<info>
                        	<ul>
                        		<li><p>再生回数：<?php echo $tp["timesplay"]; ?></p></li>
                                        <li><p>コメント数：<?php echo $tc["timescom"]; ?></p></li>
                                </ul>
                        </info>
			<table>
                                <tr><td><?php echo substr($it["intro_text"], 0, 1000); ?></td></tr>
                        </table>
			<video src='../videos_e/<?php echo substr($name["v_name"], 0, -4) . ".mp4"; ?>' controls width="620px" />
		</div>

		<div id="p2">
			<div id="p2-1">
                                <form action="" name="comment" method="post"  autocomplete="on">
                                        <p><textarea name="comment" placeholder="32文字以内でお願いします。"  required="required" rows="3" cols="50" maxlength="32"></textarea></p>
                                        <input type="submit" value="コメント" />
                                <?php
				if (isset($_REQUEST['comment'])){
				        $sql8 = sprintf('insert into comment set c_id="%d", comment="%s", v_id="%s", created="%s"',
				                $count, mysql_real_escape_string($_REQUEST['comment']), mysql_real_escape_string($_GET['vid']), date('Y.m.d H:i:s'));
				        $sql9 = sprintf('update video set times_c=times_c+1 where v_id="%s"', mysql_real_escape_string($_GET['vid']));
                                	mysql_query($sql8) or die(mysql_error());
                                	mysql_query($sql9) or die(mysql_error());
					$_REQUEST['comment']="";
				}
                                ?>
				</form>
                        </div>

			<div id="p2-2">
                		<table border="1">
                		<?php
                		while($table1 = mysql_fetch_assoc($recordSet8)){
                		?>
					<tr>
                		        <td colspan="2"><p><?php print(htmlspecialchars($table1['comment'])); ?></p></td>
                		        <td><p><?php print(htmlspecialchars($table1['created'])); ?></p></td>
                		        </tr>
				<?php
				}
				?>
				</table>
			</div>
		</div>
	</div>
  </BODY>
</HTML>
