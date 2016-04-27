<?php
require('dbconnect.php');
require('password.php');
/*
$sql_me = 'select * from user where session=' . session_id();
$recordSet_me = mysql_query($sql_me) or die(mysql_error());
$val_me = mysql_fetch_assoc($recordSet_me);
*/

//valの設定---
if (isset($_GET['uid'])) {
  $val = htmlspecialchars($_GET['val']);
  $sql_log = 'select * from action_log where u_id = ' . $Val1['id'] . ' order by id desc';
  $recordSet_log = mysql_query($sql_log) or die(mysql_error());
  } else {
  header('Location: my_page.php?uid=' . $Val1['id'] . '&val=0');
  exit();
}
//---

//majorの日本語化---
if ($Val1['major'] == 0) {
  $mymajor = '（学科/専攻未選択）';
} else if ($Val1['major'] == 1) {
  $mymajor = '教養学科/情報科学専攻';
}
//---

//アカウント情報の変更---
if (!empty($_POST['submit'])) {
//IDの変更---
  if(!empty($_POST['name'])) {
    $sql_id = 'select * from user where name="' . $_POST['name'] . '"';
    $recordSet_id = mysql_query($sql_id) or die(mysql_error());
    while ($val_log = mysql_fetch_assoc($recordSet_id)) {
      if ($val_log['name'] == $_POST['name']) {
        header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=1');
        exit();
      }
    }
    $sql1 = sprintf('update user set name = "%s" where id = "%d"', $_POST['name'], $Val1['id']);
    mysql_query($sql1) or die(mysql_error());
  }
//---

//PASSの変更---
  if(!empty($_POST['password0']) || !empty($_POST['password1']) || !empty($_POST['password2'])) {
    if (!empty($_POST['password0']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {
      if (strlen($_POST['password1']) >= 5) {
        if ($_POST['password1'] == $_POST['password2']) {
          if (password_verify($_POST['password0'], $Val1['password'])) {
            $password_hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);
            $sql2 = sprintf('update user set password = "%s" where id = "%d"', $password_hash, $Val1['id']);
            mysql_query($sql2) or die(mysql_error());
          } else {
          header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=2');
          exit();
          }
        } else {
        header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=3');
        exit();
        }
      } else {
      header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=4');
      exit();
      }
    } else {
    header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=5');
    exit();
    }
  }
//---

//iconの変更---
  if ($_FILES['userfile']['name'] != "") {
    $icon = $_FILES['userfile'];
    $type = substr($icon['name'], -4);
    if ($type == ".jpg" || $type == ".JPG" || $type == ".png" || $type == ".PNG" || $type == ".ico" || $type == ".ICO" ) {
      if (!empty($icon['tmp_name'])) {
        $iconfilePath = './pic/' . date('YmdHis') . $Val1['id'];
        move_uploaded_file($icon['tmp_name'], $iconfilePath);
        $sql3 = sprintf('update user set picture = "%s" where id = "%d"', $iconfilePath, $Val1['id']);
        mysql_query($sql3) or die(mysql_error());
      }
    } else {
      header('Location: my_page.php?uid=' . $Val1['id'] . '&val=3&error=6');
      exit();
    }
  }
//---

//学年の変更---
  if (!empty($_POST['grade'])) {
    $grade = $_POST['grade'];
    if ($grade != $Val1['grade']) {
      $sql5 = sprintf('update user set grade = "%d" where id = "%d"', $grade, $Val1['id']);
      mysql_query($sql5) or die(mysql_error());
    }
  }
//---

//性別の変更---
  if (!empty($_POST['q1'])) {
    $sex = $_POST['q1'];
    if ($sex != $Val1['sex']) {
      $sql4 = sprintf('update user set sex = "%s" where id = "%d"', $sex, $Val1['id']);
      mysql_query($sql4) or die(mysql_error());
    }
  }
//---

//学科/専攻の変更---
  if (!empty($_POST['major'])) {
    $major = $_POST['major'];
    if ($major != $Val1['major']) {
      $sql6 = sprintf('update user set major = "%d" where id = "%d"', $major, $Val1['id']);
      mysql_query($sql6) or die(mysql_error());
    }
  }
//---

//自己紹介の変更---
  if (!empty($_POST['intro'])) {
    $intro = $_POST['intro'];
    $sql7 = sprintf('update user set intro_text = "%s" where id = "%d"', $intro, $Val1['id']);
    mysql_query($sql7) or die(mysql_error());
  }
//---
  header('Location: my_page.php?uid=' . $Val1['id'] . '&val=0');
  exit();
}

//動画情報の変更---
if (!empty($_POST['submit_mp4'])) {
  if (!empty($_POST['title_mp4'])) {
    $NewTitle = mysql_real_escape_string($_POST['title_mp4']);
    $sqlNT = sprintf('update video set title="%s" where v_id="%d"', $NewTitle, $V_id);
    mysql_query($sqlNT) or die(mysql_error());
  }

  if (!empty($_POST['tag_mp4'])) {
    $NewTag = mysql_real_escape_string($_POST['tag_mp4']);
    if (substr($NewTag, -1) != " " || substr($NewTag, -3) != "　") {
      $NewTag = $NewTag . " ";
    }
    $sqlNTag = sprintf('update video set tag="%s" where v_id="%d"', $NewTag, $V_id);
    mysql_query($sqlNTag) or die(mysql_error());
  }

  if ($_FILES['sam_mp4']['name'] != "") {
    $newsam = $_FILES['sam_mp4'];
    $type = substr($newsam['name'], -4);
    if ($type == ".jpg" || $type == ".JPG" || $type == ".png" || $type == ".PNG" || $type == ".ico" || $type == ".ICO" ) {
      if (!empty($newsam['tmp_name'])) {
        $iconfilePath = '../outputTumb/' . date('YmdHis') . $Val1['id'];
        move_uploaded_file($newsam['tmp_name'], $iconfilePath);
        $sqlNS = sprintf('update video set sam = "%s" where v_id = "%d"', $iconfilePath, $Val2['v_id']);
        mysql_query($sqlNS) or die(mysql_error());
      }
    } else {
      header('Location: my_page.php?uid=' . $Val1['id'] . '&val=4&vid=' . $Val2['v_id'] . '&error=7');
      exit();
    }
  }

  if (!empty($_POST['intro_mp4'])) {
    $NewIntro = mysql_real_escape_string($_POST['intro_mp4']);
    $sqlNI = sprintf('update video set intro_text="%s" where v_id="%d"', $NewIntro, $V_id);
    mysql_query($sqlNI) or die(mysql_error());
  }
  header('Location: my_page.php?uid=' . $Val1['id'] . '&val=4&vid=' . $V_id . '&cvi=1');
  exit();
}



//---


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
            <input type="submit" name="Submit" value="検索">
          </div>
        </form>
      </li>
    </ul>
  </div>
<div id="pl">
    <h2>
      <a href="index.php"><img src="./okuvlogo.png" alt="好きなことで、生きていく。"></a>
    </h2>
    <div id="main">
      <div id = "side">
        <div id = "mp_side">
          <table>
            <tr>
              <th>
                <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=0">マイページTOP</a>
              </th>
            </tr>
            <tr>
              <th>
                <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=5">マルチ動画</a>
              </th>
            </tr>
            <tr>
              <th>
                <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=1">投稿動画</a>
              </th>
            </tr>
            <tr>
              <th>
                <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=2">視聴履歴</a>
              </th>
            </tr>
            <tr>
              <th>
                <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=3">編集</a>
              </th>
            </tr>
            <tr>
              <th>
                <a href="logout.php">ログアウト</a>
              </th>
            </tr>
          </table>
        </div>
      </div>
<!--val=0の場合 -->
      <?php if ($val == 0) { ?>
        <div id="mp_main2">
          <table class="corder">
            <tr class="minititle1">
              <th><p>アカウント情報</p></th>
            </tr>
            <tr class="name">
              <td><p><?php echo $Val1['name']; ?><img src="<?php echo $Val1['picture']; ?>" width="50px" height="50px" /></p></td>
            </tr>
            <tr class="info">
              <td><p><?php
                if ($Val1['grade'] == 0) {
                  echo '（回生未選択）';
                } else if($Val1['grade'] <= 4) {
                  echo '大学' . $Val1['grade'] . '回生';
                } else if($Val1['grade'] <= 6) {
                  $special = $Val1['grade'] - 4;
                  echo '大学院' . $special . '回生';
                }
                if ($Val1['sex'] == "none") {
                  echo "  ";
                } else if ($Val1['sex'] == "male") {
                  echo "  男性 ";
                } else if ($Val1['sex'] == "female") {
                  echo "  女性 ";
                }
                echo " " . $mymajor;
              ?></p></td>
            </tr>
            <tr class="intro">
              <td><p><?php echo $Val1['intro_text']; ?></p><td>
            </tr>
            <?php while ($log = mysql_fetch_assoc($recordSet_log)) { ?>
              <tr class="log">
                <?php if ($log['type'] == 0) { ?>
                  <td><p><?php echo $log['date'] . ' ログイン'; ?></p></td>
                <?php } else if ($log['type'] == 1) { ?>
                  <td><p><?php echo $log['date'] . ' ログアウト'; ?></p></td>
                <?php
                  } else if ($log['type'] == 4) {
                    $sql_v = sprintf('select * from video where v_id = "%d"', $log['v_id']);
                    $recordSet_v = mysql_query($sql_v) or die(mysql_error());
                    $val_v = mysql_fetch_assoc($recordSet_v);
                ?>
                  <td><p><?php echo $log['date'] . ' 投稿：<a href="player.php?vid=' . $val_v['v_id'] . '">' . $val_v['title'] . '</a>'; ?></p></td>
                <?php } else if ($log['type'] == 3) {
                  $sql_c = sprintf('select * from comment where c_id = "%d"', $log['c_id']);
                  $recordSet_c = mysql_query($sql_c) or die(mysql_error());
                  $val_c = mysql_fetch_assoc($recordSet_c);
                ?>
                  <td><p><?php echo $log['date'] . ' コメント：<a href="player.php?vid=' . $log['v_id'] . '">' . $val_c['comment']; ?></a></p></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </table>
        </div>
      <?php } ?>
<!--val=0の場合 -->
<!--val=1の場合 -->
<?php if ($val == 1) { ?>
      <div id="mp1">
        <table class="mp1">
          <tr class="title_mp1">
            <th><p>投稿動画</p></th>
          </tr>

          <?php
            $sql_mv = 'select * from video where u_id = ' . $Val1['id'] . ' order by created desc';
            $recordSet_mv = mysql_query($sql_mv) or die(mysql_error());
            $val_mv_check = 0;
              while ($val_mv = mysql_fetch_assoc($recordSet_mv)) {
                if ($val_mv['v_id'] != "") {
                  $val_mv_check = 1;
          ?>
                  <tr class="myvideo_mp1">
                    <td>
                      <div id="myvideo1_mp1">
                        <a href="player.php?vid=<?php echo $val_mv['v_id']; ?>"><img src="<?php echo '../outputTumb/' . $val_mv['sam']; ?>" width=100 height=56 /></a>
                      </div>
                      <div id="myvideo2_mp1">
                        <a href="player.php?vid=<?php echo $val_mv['v_id']; ?>"><p class="myvideo2_title_mp1">
                          <?php echo $val_mv['title']; ?>
                        </p></a>
                      </div>
                      <div id="myvideo3_mp1">
                        <ul class="myvideo3_info_mp1">
                          <li><?php echo '再生：' . $val_mv['times_p'];?></li>
                          <li><?php echo 'コメント：' . $val_mv['times_c'];?></li>
                          <li><?php echo $val_mv['created'];?></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr class="myvideo4_mp1">
                    <td>
                      <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=4&vid=<?php echo $val_mv['v_id']; ?>"><p>編集</p></a>
                    </td>
                  </tr>
                  <?php
                  }
                }
                if ($val_mv_check == 0) {
                ?>
                  <tr class="mp_none"><th><p>なにも　投稿していない。</p></th></tr>
                <?php
                }
                ?>
        </table>
      </div>
<?php } ?>
<!--val=1の場合 -->
<!--val=2の場合 -->
<?php if ($val == 2) { ?>
      <div id="mp1">
        <table class="mp1">
          <tr class="title_mp1">
            <th><p>視聴履歴</p></th>
          </tr>

          <?php
            $sql_history = 'select * from action_log where type = 2 and u_id = ' . $Val1['id'] . ' order by id desc';
            $recordSet_history = mysql_query($sql_history) or die(mysql_error());
            $val_his_check = 0;
            while ($val_history = mysql_fetch_assoc($recordSet_history)) {
              $sql_myvideo = 'select * from video where v_id = ' . $val_history['v_id'];
              $recordSet_myvideo = mysql_query($sql_myvideo) or die(mysql_error());
              $val_myvideo = mysql_fetch_assoc($recordSet_myvideo);
              if (!empty($val_myvideo)) {
                $val_his_check = 1;
          ?>
                <tr class="mp2">
                  <td>
                    <div id="myvideo1_mp1">
                      <a href="player.php?vid=<?php echo $val_myvideo['v_id']; ?>"><img src="<?php echo '../outputTumb/' . $val_myvideo['sam']; ?>" width=100 height=56 /></a>
                    </div>
                    <div id="myvideo2_mp1">
                      <a href="player.php?vid=<?php echo $val_myvideo['v_id']; ?>"><p class="myvideo2_title_mp1">
                        <?php echo $val_myvideo['title']; ?>
                      </p></a>
                    </div>
                    <div id="myvideo3_mp1">
                      <ul class="myvideo3_info_mp1">
                        <li><?php echo '再生：' . $val_myvideo['times_p'];?></li>
                        <li><?php echo 'コメント：' . $val_myvideo['times_c'];?></li>
                        <li><?php echo $val_history['date'];?></li>
                      </ul>
                    </div>
                  </td>
                </tr>
            <?php
              }
            }
            if ($val_his_check == 0) {
            ?>
              <tr class="mp_none"><th><p>なにも　視聴していない。</p></th></tr>
            <?php
            }
            ?>
          </table>
        </div>
<?php } ?>
<!--val=2の場合 -->
<!--val=3の場合 -->
<?php if ($val == 3) { ?>
      <div id="mp_val3_1">
        <form action="" method="post" id="remake" name="remake" autocomplete="on" enctype="multipart/form-data">
          <table class="mp_val3">
            <tr class="minititle2">
              <th colspan="2"><p>アカウント情報 編集</p><th>
            </tr>
            <tr class="mp_val3_id">
              <td class="c"><p>ID</p>
                <?php
                  if ($_GET['error'] == 1) {
                    echo '<p class="mp_error">*そのIDでは登録できません。</p>';
                  }
                ?></td>
              <td class="d">
                <input type="text" placeholder="1～16文字" value="<?php
                  if (!empty($_POST['name'])) {
                    echo $_POST['name'];
                  }
                  ?>" style="width: 400px;" id="name" name="name" size="64px" maxlength="16">
              </td>
            </tr>
            <tr class="mp_val3_pass1">
              <td class="a"><p>現在のPASSWORD</p>
                <?php
                  if ($_GET['error'] == 2) {
                    echo '<p class="mp_error">*パスワードが違います</p>';
                  } else if ($_GET['error'] == 5) {
                    echo '<p class="mp_error">*パスワード変更時には必須の項です。</p>';
                  }
                ?>
              </td>
              <td class="b">
                 <input type="password" placeholder="5～16文字（パスワードを更新する場合に入力）" style="width: 400px;" id="password0" name="password0" value=""  size="64px" maxlength="16">
              </td>
            </tr>
            <tr class="mp_val3_pass2">
              <td class="a"><p>新しいPASSWORD</p>
                <?php
                  if ($_GET['error'] == 3) {
                    echo '<p class="mp_error">*入力されたパスワードが一致していません。</p>';
                  } else if ($_GET['error'] == 4) {
                    echo '<p class="mp_error">*パスワードは5文字以上でご登録下さい。</p>';
                  } else if ($_GET['error'] == 5) {
                    echo '<p class="mp_error">*パスワード変更時には必須の項です。</p>';
                  }
                ?>
              </td>
              <td class="b">
                 <input type="password" placeholder="5～16文字（パスワードを更新する場合に入力）" style="width: 400px;" id="password1" name="password1" value=""  size="64px" maxlength="16">
              </td>
            </tr>
            <tr class="mp_val3_pass3">
              <td class="a"><p>新しいPASSWORD（確認）</p>
                <?php
                  if ($_GET['error'] == 3) {
                    echo '<p class="mp_error">*入力されたパスワードが一致していません。</p>';
                  } else if ($_GET['error'] == 5) {
                    echo '<p class="mp_error">*パスワード変更時には必須の項です。</p>';
                  }
                ?>
              </td>
              <td class="b">
                <input type="password" placeholder="一つ上と同じものを入力（パスワードを更新する場合に入力）" style="width: 400px;" id="password2" name="password2" value=""  size="64px" maxlength="32">
              </td>
            </tr>
            <tr class="mp_val3_icon">
              <td class="a"><p>icon 【.jpg .png .ico のみ】</p>
                <?php
                  if ($_GET['error'] == 6) {
                    echo '<p class="mp_error">*ファイルの形式をお確かめ下さい。</p>';
                  }
                ?>
              </td>
              <td class="b">
                <input type="file" id="userfile" name="userfile" size="50px" />
              </td>
            </tr>
            <tr class="mp_val3_grade">
              <td class="a"><p>学年</p></td>
              <td class="b">
                <p>
                  <select name="grade">
                    <?php
                      $i = 0;
                      while ($i <= 7) {
                        echo '<option value=' . $i;
                        if ($i == $Val1['grade']) {
                          echo ' selected';
                        }
                        echo '>';
                        if ($i == 0) {
                          echo '未選択';
                        } else if ($i <= 4) {
                          echo '大学' . $i . '回生';
                        } else if ($i <= 6){
                          $j = $i - 4;
                          echo '大学院' . $j . '回生';
                        } else {
                          echo '上記以外';
                        }
                        echo '</option>';
                        $i++;
                      }
                    ?>
                  </select> 回生
                </p>
              </td>
            </tr>
            <tr class="mp_val3_sex">
              <td class="a"><p>性別</p></td>
              <td class="b">
                <p>
                  <input type="radio" name="q1" value="none"<?php if ($Val1['sex'] == 'none') { echo ' checked'; }?>> 未選択
                  <input type="radio" name="q1" value="male"<?php if ($Val1['sex'] == 'male') { echo ' checked'; }?>> 男性
                  <input type="radio" name="q1" value="female"<?php if ($Val1['sex'] == 'female') { echo ' checked'; }?>> 女性
                </p>
              </td>
            </tr>
            <tr class="mp_val3_major">
              <td class="a"><p>学科/専攻</p></td>
              <td class="b">
                <p>
                  <select name="major">
                  <option value=0 selected>未選択</option>
                  <option value=1>教養学科/情報科学専攻</option>
                  </select>
                </p>
              </td>
            </tr>
            <tr class="mp_val3_intro">
              <td class="a"><p>自己紹介</p></td>
              <td class="b">
                <textarea style="resize: vertical; width: 400px;" placeholder="" name="intro" cols="70" rows="5" maxlength="256"><?php echo $Val1['intro_text']; ?></textarea>
              </td>
            </tr>
            <tr class="mp_val3_submit">
              <td colspan="2"><p><input type="submit" id="submit" name="submit" style="width: 300px;" value="登録する" /></p></td>
            </tr>
          </table>
        </form>
      </div>
<?php } ?>
<!--val=3の場合 -->

<!--val=4の場合 -->
<?php if ($val == 4) { ?>
  <div id="mp4">
    <form action="" method="post" id="remake_mp4" name="remake_mp4" autocomplete="on" enctype="multipart/form-data">
      <table class="mp4">
        <tr class="title_mp4"><th class="title_mp4" colspan="2">
          <p>動画情報</p>
          <?php
            if ($_GET['cvi'] == 1) {
          ?>
          <p class="mp4_collect">動画情報を変更しました。</p></th>
          <?php } ?>
        </tr>
        
        <tr class=top_mp4>
          <td class="topl_mp4"><p>タイトル</p></td>
          <td class="topr_mp4">
            <input type="text" placeholder="1～16文字" value="<?php
                if (isset($Val2['title'])) {
                  echo $Val2['title'];
                }
              ?>" id="title_mp4" name="title_mp4" size="76px" maxlength="16">
          </td>
        </tr>
        <tr class="column_mp4">
          <td class="columnl_mp4"><p>タグ</p></td>
          <td class="columnr_mp4">
            <input type="text" placeholder="1～128文字" value="<?php
                if (isset($Val2['tag'])) {
                  echo $Val2['tag'];
                }
              ?>" id="tag_mp4" name="tag_mp4" size="76px" maxlength="128">
            <p class="mp4">*タグはスペースで区切ると複数設定できます。</p>
          </td>
        </tr>
        <tr class="column_mp4">
          <td class="columnl_mp4"><p>サムネ</p></td>
          <td class="columnr_mp4">
            <p><input type="file" id="sam_mp4" name="sam_mp4" size="40px" /></p>
            <?php
                  if ($_GET['error'] == 7) {
                    echo '<p class="mp_error">*ファイルの形式をお確かめ下さい。</p>';
                  }
                ?>
          </td>
        </tr>
        <tr class="bottom_mp4">
          <td class="bottoml_mp4"><p>説明文</p></td>
          <td class="bottomr_mp4">
            <textarea style="resize: vertical;" placeholder="" name="intro_mp4" cols="76px" rows="5" maxlength="1000"><?php echo $Val2['intro_text']; ?></textarea>
          </td>
        </tr>
        <tr class="mp_val3_submit">
          <td colspan="2"><p><input type="submit" id="submit_mp4" name="submit_mp4" style="width: 300px;" value="登録する" /></p></td>
        </tr>
      </table>
    </form>
  </div>

<?php } ?>
<!--val=4の場合 -->

<!--val=5の場合-->
<?php 
  if ($val == 5) {

    if (isset($_POST['order'])) {
      $order = htmlspecialchars($_POST['order']);
    } else {
      $order = 0;
    }

    $sql_mp5 = "select * from video";

    if ($order == 0) {
      $sql_mp5 = $sql_mp5 . " order by created desc";
    } else if ($order == 1) {
      $sql_mp5 = $sql_mp5 . " order by created asc";
    } else if ($order == 2) {
      $sql_mp5 = $sql_mp5 . " order by times_p desc";
    } else if ($order == 3) {
      $sql_mp5 = $sql_mp5 . " order by times_p asc";
    } else if ($order == 4) {
      $sql_mp5 = $sql_mp5 . " order by times_c desc";
    } else if ($order == 5) {
      $sql_mp5 = $sql_mp5 . " order by times_c asc";
    }
    $recordSet_mp5 = mysql_query($sql_mp5) or die(mysql_error());
?>
  <div id="mp5">
    <p class="title">マルチ動画を見る</p>
<?php
    if (!isset($_GET['vid1'])) {
?>
      <div id="topinfo_mp5"><p>マルチ動画は2動画同時再生機能です．まず，主となる動画を選択して下さい．</p></div>
<?php
    } else if (isset($_GET['vid1']) && !isset($_GET['vid2'])) {
      $vid1 = htmlspecialchars($_GET['vid1']);
      $sql_mp5_2 = sprintf('select * from video where v_id="%d"', $vid1);
      $recordSet_mp5_2 = mysql_query($sql_mp5_2) or die(mysql_error());
      $val_mp5_2 = mysql_fetch_assoc($recordSet_mp5_2);
?>
      <div id="topinfo_mp5"><p>次に，副となる動画を選択してください．</p></div>
      <p>選択中の主動画</p>
      <table class="mp5_2">
        <tr class="mp5">
          <td>
            <div id="sam_mp5">
              <img src="<?php echo '../outputTumb/' . $val_mp5_2['sam']; ?>" width=100 height=56 />
            </div>
            <div id="info_mp5">
              <ul class="mp5">
                <li><p><?php echo $val_mp5_2['title']; ?></p></li>
                <li><?php echo '再生：' . $val_mp5_2['times_p']; ?></li>
                <li><?php echo 'コメント：' . $val_mp5_2['times_c']; ?></li>
                <li><?php echo $val_mp5_2['created']; ?></li>
              </ul>
            </div>
          </td>
        </tr>
      </table>
<?php
    } else if (isset($_GET['vid1']) && isset($_GET['vid2'])) {
      $vid1 = htmlspecialchars($_GET['vid1']);
      $vid2 = htmlspecialchars($_GET['vid2']);
      $sqlMulticheck = "select * from video where multi=1 and ";
      header('Location: player.php?multicheck=1&vid1=' . $vid1 . '&vid2=' . $vid2);
      exit();
    }
?>

    <form name="mp5_order" method="post" action="my_page.php?uid=<?php echo $Val1['id']; ?>&val=5">
            <select name="order" id="order">
              <?php
              $orders = array('新しい順', '古い順', '再生回数多い順', '再生回数>少ない順', 'コメント数多い順', 'コメント数少ない順');
              $i = 0;
              while ($i <= 5) {
                echo '<option value="' . $i . '"';
                if (isset($order)) {
                  if ($order == $i) {
                    echo ' selected';
                  }
                }
              echo '>' . $orders[$i] . '</option>';
              $i++;
              }
              ?>
            </select>
            <input type="submit" name="Submit" value="並び替え" />
          </form>
<?php
    while ($val_mp5 = mysql_fetch_assoc($recordSet_mp5)) {
      if (!isset($vid1)) {
?>    
        <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=5&vid1=<?php echo $val_mp5['v_id']; ?>">
<?php
      } else if (isset($vid1)) {
        $vid1 = htmlspecialchars($_GET['vid1']);
?>
        <a href="my_page.php?uid=<?php echo $Val1['id']; ?>&val=5&vid1=<?php echo $vid1; ?>&vid2=<?php echo $val_mp5['v_id']; ?>">
<?php
      }
?>
      <table class="mp5">
        <tr class="mp5">
          <td>
            <div id="sam_mp5">
              <img src="<?php echo '../outputTumb/' . $val_mp5['sam']; ?>" width=100 height=56 />
            </div>
            <div id="info_mp5">
              <ul class="mp5">
                <li><p><?php echo $val_mp5['title']; ?></p></li>
                <li><?php echo '再生：' . $val_mp5['times_p']; ?></li>
                <li><?php echo 'コメント：' . $val_mp5['times_c']; ?></li>
                <li><?php echo $val_mp5['created']; ?></li>
              </ul>
            </div>
          </td>
        </tr>
      </table></a>
<?php
    }
?>
    </div>
<?php
  }
?>
  </div>
</body>
</html>
