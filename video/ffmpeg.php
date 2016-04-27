<?php
//ルートURL定義
$myURL = "http://west.ss.osaka-kyoiku.ac.jp/video/";

//----------------------------------------------------ファイル保存初期設定
//ファイルを保存しておくディレクトリ
$inputFile_path = '/var/www/html/video/hoge/';

//保存するファイル名
$file_path = $inputFile_path . $_FILES["uploadfile"]["name"];

//----------------------------------------------------FFmpeg初期設定
//FFmpegの場所
$ffmpeg_path = '/usr/local/src/ffmpeg';

//変換後のファイルがある場所（ドキュメントルート配下）
$outputFile_dir = '/var/www/html/video/hoge/';

//変換後のファイル名
$outputFile_name = 'out.flv';
$outputFile_path = $outputFile_dir . $outputFile_name;

//変換オプションその１
$command_option1 = ' -y -i ';

//変換オプションその２
$command_option2 = ' -f flv -vcodec flv -r 25 -b 900k -s qvga -acodec libmp3lame-ar 44100 -ab 64k ';

//サムネイル初期設定
$tumbFile_name = 'out.jpg';
$tumbFile_path = $outputFile_dir . $tumbFile_name;

//サムネイルオプションその２
$tumbFile_option2 = ' -f image2 -s qvga -ss 3 -r 1 -t 0:0:0.001 -an ';

//アップロードされたファイルを受け取る
if (move_uploaded_file($_FILES["uploadfile"]["tmp_name"],$file_path)){

//退避されたファイルを読み取れるようにする
chmod ($file_path,0644);

//パス指定
$video_dir = '/var/www/html/video/hoge/';
$video_path = $video_dir . $_FILES["uploadedfile"]["name"];

//----------------------------------------------------FLV変換
//変換スクリプト作成
$command_line_video = $ffmpeg_path . $command_option1 . $video_path .
$command_option2 . $outputFile_name;

//FLV変換コマンド実行
$last_line_video = system($command_line_video, $retval_video);

//----------------------------------------------------Flash
echo '<script type="text/javascript" src="' . $myURL . 'ffmpeg.js"></script>';
echo '<script language="javascript">foutput("' . $myURL . '", "' . $outputFile_name . '");</script>';

//----------------------------------------------------サムネイル生成
//サムネイル生成コマンド実行
$last_line_img = system($command_line_img, $retval_img);
echo '<p><img src="' . $myURL . $tumbFile_name . '" /></p>';
}
?>

<form action="study_fileview.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
<p>
	<input name="MAX_FILE_SIZE" type="hidden" value="100000000" />
	<!-- タイトル：
	<input name="comment" type="text" id="comment" /> -->
</p>
<p>
	ファイル
	：
	<input name="uploadfile" />
</p>
<p>
	<input type="submit" name="Submit" value="送信" />
</p>
<p>&nbsp; </p>
</form>
