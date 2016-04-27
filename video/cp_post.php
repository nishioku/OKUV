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
    $sql2 = sprintf('select * from user where session="%s"', session_id());
    $recordSet1 = mysql_query($sql2) or die(mysql_error());
    $user = mysql_fetch_assoc($recordSet1);
  }


if (!empty($_POST)) {
	//　エラー項目の確認
	/*if ($_POST['title'] == '') {
		$error['title'] = 'blank';
	}
        if ($_FILES['video']['name']=="") {
                $error['video'] = 'blank';
        }
	$fileName = $_FILES['video']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'mp4' && $ext != 'ogv') {
			$error['video'] = 'type';
		}
	}*/
	if (empty($error)) {
		//動画をアップロードする
                  //original_nameの設定---
		  $video1 = htmlspecialchars($_FILES['video']['name']);
                  //---
                  //v_nameの設定--- 
		  $video = date('YmdHis') . $user['id'] . ".mp4";
                  //---
/*エンコード機能---------------------------------------------------------------------------------------------------------------*/
		//ルートURL定義
		$myURL = "http://west.ss.osaka-kyoiku.ac.jp/video/";
		//----------------------------------------------------ファイル保存初期設定
		//ファイルを保存しておくディレクトリ
		$inputFile_path = '../posted_videos/';
		//保存するファイル名
		$file_path = $inputFile_path . $video;
		//----------------------------------------------------FFmpeg初期設定
		//FFmpegの場所
		$ffmpeg_path = '/usr/local/bin/ffmpeg';
		//変換後のファイルがある場所（ドキュメントルート配下）
		$outputFile_dir = '../videos_e/';
		//変換後のファイル名
		$outputFile_name = $video1;
		$outputFile_path = $outputFile_dir . $outputFile_name;
		//変換オプションその１
		$command_option1 = ' -y -i ';
		//変換オプションその２
		$command_option2 = ' -vcodec libx264 ';
		//サムネイル初期設定
		$tumbFile_name = substr($video, 0, -4) . ".jpg";
		//サムネの保存場所
		$outputTumb_dir = '../outputTumb/';
		$tumbFile_path = $outputTumb_dir . $tumbFile_name;
		//サムネイルオプション
		$tumbFile_option = ' -f image2 -s qqvga -ss 10 -r 1 -t 0:0:0.001 -an ';
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
		$_SESSION['join']['video'] = $video;
                $_SESSION['join']['video1'] = $video1;
		header('Location: check.php');
		exit();
		}
	}
	//書き直し
	if ($_REQUEST['action'] == 'rewrite') {
	$_POST = $_SESSION['join'];
	$error['rewrite'] = true;
}
?>

<html>
<head>
        <link rel="icon" type="image/vnd.microsoft.icon" href="okuvideo.ico">
        <link rel="stylesheet" href="style.css">
        <title>投稿フォーム -OKUV</title>
</head>
<h1>
      <ul>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="post.php">投稿する</a></li>
        <li><a href="index.php">マイページ</a></li>
        <li style="width: 40px;height: 25px;"><img src="<?php echo $user['picture']; ?>" width="25" height="25"></li>
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
<body>

<div id="page">
<div id="po1">
<form action="post.php" method="post" autocomplete="on" enctype="multipart/form-data">
	<table>
		<tr>
			<th colspan="2">動画を投稿する</th>
		</tr>

		<tr>
			<td>タイトル</td>
			<td>
				<input type="text" placeholder="必須" required="required" name="title" size="64" maxlength="32"
					value="<?php echo htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'); ?>" >
			</td>
		</tr>

		<tr>
			<td>ファイル</td>
			<td>
				<input type="file" name="video" size="50" required="required" />
					<?php if ($error['video'] == 'type'): ?>
					<p class="error"></p>
					<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td>説明文</td>
			<td>
                                <textarea style="resize: vertical;"placeholder="必須" required="required" name="intro_text" cols="70" rows="5" maxlength="256" 
                                        value="<?php echo htmlspecialchars($_POST['intro_text'], ENT_QUOTES, 'UTF-8'); ?>" ></textarea>
                        </td>
                </tr>

		<tr>
                        <th colspan="2"><input type="submit" value="投稿を確認する" /></th>
                </tr>
	</table>
</form>
</div>
</div>
</body>
</html>
