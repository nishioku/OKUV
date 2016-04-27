<?php
require('dbconnect.php');

//動画をアップロードする
//original_nameの設定---
if (!empty($_FILES['video']['name'])) {
  $original_name = htmlspecialchars($_FILES['video']['name']);
//---
//v_nameの設定--- 
  $v_name = date('YmdHis') . $Val1['id'] . ".mp4";
  //---
/*エンコード機能---------------------------------------------------------------------------------------------------------------*/
  //ルートURL定義
  $myURL = "http://west.ss.osaka-kyoiku.ac.jp/video/";
  //----------------------------------------------------ファイル保存初期設定
  //ファイルを保存しておくディレクトリ
  $inputFile_path = '../posted_videos/';
  //保存するファイル名
  $file_path = $inputFile_path . $v_name;
  //----------------------------------------------------FFmpeg初期設定
  //FFmpegの場所
  $ffmpeg_path = '/usr/local/bin/ffmpeg';
  //変換後のファイルがある場所（ドキュメントルート配下）
  $outputFile_dir = '../videos_e/';
  //変換後のファイル名
  $outputFile_name = $v_name;
  $outputFile_path = $outputFile_dir . $outputFile_name;
  //変換オプションその１
  $command_option1 = ' -y -i ';
  //変換オプションその２
  //$command_option2 = ' -vcodec libx264 ';
  $command_option2 = ' -vcodec libx264 -s 512x288 -aspect 16:9 ';
  //サムネイル初期設定
  $tumbFile_name = substr($v_name, 0, -4) . ".jpg";
  //サムネの保存場所
  $outputTumb_dir = '../outputTumb/';
  $tumbFile_path = $outputTumb_dir . $tumbFile_name;
  //サムネイルオプション
  $tumbFile_option = ' -f image2 -s qqvga -ss 1 -r 1 -t 0:0:0.001 -an ';
  //アップロードされたファイルを受け取る
  if (move_uploaded_file($_FILES["video"]["tmp_name"], $file_path)){
    //退避されたファイルを読み取れるようにする
    chmod ($file_path,0644);
    //----------------------------------------------------FLV変換
    //変換スクリプト作成
    $command_line_video = $ffmpeg_path . $command_option1 . $file_path . $command_option2 . $outputFile_path;
    //FLV変換コマンド実行
    $last_line_video = system($command_line_video, $retval_video);
    //----------------------------------------------------サムネイル生成
    //サムネイル作成コマンド生成
    $command_line_img = $ffmpeg_path . $command_option1 . $outputFile_path . $tumbFile_option . $tumbFile_path;
    //サムネイル作成コマンド実行
    $last_line_img = system($command_line_img, $retval_img);
  }
/*---------------------------------------------------------------------------------------------------------------------------*/
  $_SESSION['join'] = $_POST;
  $_SESSION['join']['video'] = $v_name;
  $_SESSION['join']['video1'] = $original_name;
  header('Location: check.php');
  exit();
}
//書き直し
if ($_REQUEST['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
}
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
    <div id="page">
      <div id="post">
        <form action="post.php" method="post" autocomplete="on" enctype="multipart/form-data">
	  <table class="post">
            <tr class="title_po">
              <th colspan="2">動画を投稿する</th>
            </tr>
            <tr class="top_po">
              <td class="topl_po">タイトル</td>
              <td class="topr_po">
                <input type="text" placeholder="必須" required="required" name="title" size="64px" maxlength="32"
                   value="<?php echo htmlspecialchars($_SESSION['join']['title'], ENT_QUOTES, 'UTF-8'); ?>" >
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">ファイル</td>
              <td class="columnr_po">
                <input type="file" name="video" size="50px" required="required" />
                  <?php if ($error['video'] == 'type'): ?>
                    <p class="error"></p>
                  <?php endif; ?>
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">カテゴリ</td>
              <td class="columnr_po">
                <input type="text" placeholder="動画のカテゴリを設定してください。" name="category" size="64px" maxlength="32"
                   value="<?php echo htmlspecialchars($_SESSION['join']['category'], ENT_QUOTES, 'UTF-8'); ?>" >
              </td>
            </tr>
            <tr class="column_po">
              <td class="columnl_po">タグ</td>
              <td class="columnr_po">
                <input type="text" placeholder="スペースを挟むと複数設定できます（例：教育 資料 勉強" name="tag" size="64px" maxlength="32"
                   value="<?php echo htmlspecialchars($_SESSION['join']['tag'], ENT_QUOTES, 'UTF-8'); ?>" >
              </td>
            </tr>
            <tr class="bottom_po">
              <td class="bottoml_po">説明文</td>
              <td class="bottomr_po">
                <textarea style="resize: vertical;"placeholder="必須" required="required" name="intro_text" cols="64px" rows="5px" maxlength="256" 
                   value="<?php echo htmlspecialchars($_SESSION['join']['intro_text'], ENT_QUOTES, 'UTF-8'); ?>" ></textarea>
              </td>
            </tr>
            <tr class="submit_po">
              <th colspan="2"><p>
                  <input type="submit" value="投稿を確認する" />
              </p></th>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
  </body>
</html>
