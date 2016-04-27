<?php
require('dbconnect.php');
require('tag_splitter.php');

if (isset($_GET['multicheck'])) {
  $multi_check = htmlspecialchars($_GET["multicheck"]);
  $vid1 = htmlspecialchars($_GET["vid1"]);
  $vid2 = htmlspecialchars($_GET["vid2"]);

  $sql_m1 = "select * from video where v_id=" . $vid1;
  $sql_m2 = "select * from video where v_id=" . $vid2;

  $recordSet_m1 = mysql_query($sql_m1) or die(mysql_error());
  $recordSet_m2 = mysql_query($sql_m2) or die(mysql_error());

  $val_m1 = mysql_fetch_assoc($recordSet_m1);
  $val_m2 = mysql_fetch_assoc($recordSet_m2);
} else {
  $multi_check = 0;
}

//action_logのidを取得---
  $sql1 = sprintf('select count(*) as count from action_log');
    $recordSet1 = mysql_query($sql1) or die(mysql_error());
    $count = mysql_fetch_assoc($recordSet1);
//---

//最後に見た動画を参照---
  $sql2 = sprintf('select * from action_log where u_id="%d" and type=2 order by id desc limit 1', $Val1['id']);
    $recordSet2 = mysql_query($sql2) or die(mysql_error());
    $last_video = mysql_fetch_assoc($recordSet2);
//---

//見たことのある動画だった場合---
  $sql3 = sprintf('update video set times_p = times_p + 1 where v_id="%d"', $Val2['v_id']);
  $sql4 = sprintf('select * from action_log where id = "%d"', $last_video['id']);
  $sql5 = sprintf('insert into action_log set id = "%d",
                u_id = "%d",
                type = 2,
                date = "%s",
                v_id = "%d",
                session = "%s"',
                $count['count'],
                $Val1['id'],
                date('Y-m-d H:i:s'),
                $Val2['v_id'],
                $U_se);
  $recordSet4 = mysql_query($sql4) or die(mysql_error());
  $last_action = mysql_fetch_assoc($recordSet4);
  if ($last_action['v_id'] != $Val2['v_id']) {
    mysql_query($sql3) or die(mysql_error());
    $count = mysql_fetch_assoc($recordSet1);
    mysql_query($sql5) or die(mysql_error());
    if ($multi_check == 0) {
    header('Location: player.php?vid=' . $Val2['v_id']);
    exit();
  } else {
    header('Location: player.php?multicheck=1&vid1=' . $vid1 . '&vid2=' . $vid2);
    exit();
  }
  }
    
//---
//
if (!empty($_POST['text_pl'])){
  $sql7 = sprintf('select count(*) as count from comment');
  $recordSet7 = mysql_query($sql7) or die(mysql_error());
  $val7 = mysql_fetch_assoc($recordSet7);
  $sql8 = sprintf('insert into comment set c_id="%d", comment="%s", v_id="%d", u_id="%d", created="%s"',
                     $val7['count'], $_POST['text_pl'], $Val2['v_id'], $Val1['id'], date('Y-m-d H:i:s'));
  $sql9 = sprintf('update video set times_c=times_c+1 where v_id="%d"', $Val2['v_id']);
  mysql_query($sql8) or die(mysql_error());
  mysql_query($sql9) or die(mysql_error());
  //$sql12 = sprintf('select max(c_id) as id from comment where comment="%s" and u_id="%d"', $_POST['text_pl'], $Val1['id']);
  //$recordSet12 = mysql_query($sql12) or die(mysql_error());
  //$val12 = mysql_fetch_assoc($recordSet12);
  $sql11 = sprintf('insert into action_log set id = "%d", u_id = "%d", type = 3,
                    date = "%s",
                    v_id = "%d", c_id = "%d", session = "%s"',
                    $count['count'], $Val1['id'], date('Y-m-d H:i:s'),
                    $Val2['v_id'], $val7['count'], $U_se); 
  $count = mysql_fetch_assoc($recordSet1);
  mysql_query($sql11) or die(mysql_error());
  //$_REQUEST['comment']="";
  if ($multi_check == 0) {
    header('Location: player.php?vid=' . $Val2['v_id']);
    exit();
  } else {
    header('Location: player.php?multicheck=1&vid1=' . $vid1 . '&vid2=' . $vid2);
    exit();
  }
}

$sql_pl3_1 = 'select * from user where id=' . $Val2['u_id'];
$recordSet_pl3_1 = mysql_query($sql_pl3_1) or die(mysql_error());
$val_pl3_1 = mysql_fetch_assoc($recordSet_pl3_1);

?>
  
<html>
  <head>
    <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
    <link rel="stylesheet" href="style.css">
    <title><?php echo $Val2['title']; ?> -OKUV</title>
    <script src="jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
    
    $(function() {    

      var video1 = document.getElementById("Video1");
      video1.addEventListener("timeupdate",seekbar,false);

      function seekbar(){
          var fullTime = video1.duration;                 // 動画全体の時間
          var nowTime = video1.currentTime;               // 現在の再生時間
          var setPos = (nowTime/fullTime)*100;    // 全体に対する現在の位置

          $('#currenttime').css({'margin-left':setPos+'%'});
 
          var nowBuffered = video1.buffered.end(0);               // 現在のバッファ時間
          var setBufs = (nowBuffered/fullTime)*100;       // 全体に対する現在の位置

          $('#load').css({'width':setBufs+'%'});
          target = document.getElementById("movieTime1");
          target.innerHTML = Math.floor(nowTime);
          target = document.getElementById("movieTime2");
          target.innerHTML = Math.floor(fullTime);
      }
    });

    function vidplay() {
       var video1 = document.getElementById("Video1");
       var video2 = document.getElementById("Video2");
       var button = document.getElementById("play");
       if (video1.paused) {
          video1.play();
          video2.play();
          button.textContent = "一時停止";
       } else {
          video1.pause();
          video2.pause();
          button.textContent = "　再生　";
       }
    }

    function restart() {
        var video1 = document.getElementById("Video1");
        var video2 = document.getElementById("Video2");
        var button = document.getElementById("play");
        video1.currentTime = 0;
        video2.currentTime = 0;
        video1.pause();
        video2.pause();
        button.textContent = "　再生　";
    }

    function skip(value) {
        var video1 = document.getElementById("Video1");
        var video2 = document.getElementById("Video2");
        var button = document.getElementById("play");
        video1.pause();
        video2.pause();
        video1.currentTime += value;
        video2.currentTime = video1.currentTime;
        video1.play();
        video2.play();
        button.textContent = "一時停止";
    }

    function syncro() {
        var video1 = document.getElementById("Video1");
        var video2 = document.getElementById("Video2");
        var button = document.getElementById("play");

        video1.pause();
        video2.pause();

        video1.currentTime = Math.floor(video1.currentTime);
        video2.currentTime = video1.currentTime;

        button.textContent = "　再生　";
    }
	
  </script>
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
<?php if ($multi_check == 0) { ?>
    <div id='pl_video1'>
      <div id="pl_video2">
        <video src='../videos_e/<?php echo $Val2['v_name']; ?>' controls width=1030px >
      </div>
    </div>
<?php } else if ($multi_check == 1) { ?>
    <div id="pl_video1">
      <div id="pl_video2">
        <video id="Video1" >
          <source src="../videos_e/<?php echo $val_m1['v_name']; ?>" type="video/mp4" width=515px>
        </video>
        <video id="Video2" >
          <source src="../videos_e/<?php echo $val_m2['v_name']; ?>" type="video/mp4" width=515px muted >
        </video>
        <div id="slider">
	  <div id="load"></div>
	  <div tyle="color: #eee;" id="currenttime"></div>
        </div>
        <div id="buttonbar">
          <div>
            <span style="color: #eee;" id="movieTime1">0</span>
            <span style="color: #eee;"> / </span>
            <span style="color: #eee;" id="movieTime2">0</span>
          </div>
          <ul>
            <li><button id="restart" onclick="restart();">■</button></li>
            <li><button id="rew1" onclick="skip(-10)">10秒＜＜</button></li>
            <li><button id="rew2" onclick="skip(-1)">1秒＜＜</button></li>
            <li><button id="play" onclick="vidplay()">　再生　</button></li>
            <li><button id="fastFwd1" onclick="skip(1)">＞＞1秒</button></li>
            <li><button id="fastFwd2" onclick="skip(10)">＞＞10秒</button></li>
            <li><button id="cyncro" onclick="syncro();">シンクロ</button></li>
          <ul>
        </div>
      </div>
    </div>
<?php } ?>

    <div id='pl_main'>

      <div id='pl_title'>
        <a href="<?php echo 'player.php?vid=' . $Val2['v_id']; ?>"><p><?php echo $Val2['title']; ?></p></a>
      </div>

      <div id='pl_tag'>
        <ul>
          <?php
            $j = 0;
            while ($i > $j) {
          ?>
              <a href="tag_serch.php?page=1&tag=<?php echo $Val4[$j]; ?>"><li><p><?php echo $Val4[$j]; ?></p></li></a>
          <?php
              $j++;
            }
          ?>
        </ul>
      </div>

      <div id="pl_intro">
        <p><?php echo $Val2['intro_text']; ?></p>
      </div>

      <div id="pl_info">
        <div id="pl_info_sam">
          <p><img src='../outputTumb/<?php echo $Val2['sam']; ?>' width="160px" height="90px" /></p>
        </div>

        <div id='pl_info_info'>
          <ul>
            <li><p>再生回数：<?php echo $Val2['times_p']; ?></p></li>
            <li><p>コメント数：<?php echo $Val2['times_c']; ?></p></li>
            <li><p>投稿：<?php echo $Val2['created']; ?></p></li>
          </ul>
        </div>
      </div>

      <div id="pl_poster">
        <div id='pl_poster_pict'>
          <p><img src='<?php echo $val_pl3_1['picture']; ?>' width=50 height=50 /></p>
        </div>

        <div id="pl_poster_name">
          <p><?php echo $val_pl3_1['name']; ?></p>
        </div>
      </div>

      <div id="pl_comment">
        <form action="" name="comment" method="post"  autocomplete="on">
          <ul>
            <li><input type=text placeholder="32文字以内でお願いします。" id="text_pl" name="text_pl" required="required" size="60px" maxlength=32></li>
            <li><input type="submit" value="コメント" /></li>
          </ul>
        </form>
      </div>

      <div id='pl_comview'>
        <?php
          $recordSet_com = mysql_query($Sql3) or die(mysql_error());
          while($val_com = mysql_fetch_assoc($recordSet_com)){
            $sql_pl5_1 = 'select * from user where id=' . $val_com['u_id'];
            $recordSet_pl5_1 = mysql_query($sql_pl5_1) or die(mysql_error());
            $val_pl5_1 = mysql_fetch_assoc($recordSet_pl5_1);
        ?>
            <div id='pl_comview1'>
              <div id="pl_comview2">
                <p><img src='<?php echo $val_pl5_1['picture']; ?>' width="54px" height="54px" /></p>
              </div>

              <div id='pl_comview3'>
                  <ul>
                    <li class="pl1"><?php echo $val_pl5_1['name']; ?></li>
                    <li class="pl2"><?php echo $val_com['created']; ?></li>
                  </ul>
                <div id="hukidashi">
                  <table>
                    <tr>
                      <td class='hukidashi0'>
                      </td>
                      <td class='hukidashi1'>
                      </td>
                    </tr>
                    <tr>
                      <td class='hukidashi2'>
                      </td>
                      <td class='hukidashi3'>
                       <p><?php echo $val_com['comment']; ?></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

        <?php
          }
        ?>
      </div>
    </div>
  </div>
  </body>
</html>

