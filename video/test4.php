<?php
require('dbconnect.php');

$videoPlus = 3;
$count = 0;

while ($count <= $videoPlus) {

  $sql1 = "select max(v_id) as maxvid from video";
    $recordSet1 = mysql_query($sql1) or die(mysql_error());
    $val1 = mysql_fetch_assoc($recordSet1);
    $val1['maxvid']++;

  $uid = rand(0, 11);
  $timesp = rand(100, 999999);
  $title = "test" . $val1['maxvid'];

  $sql2 = sprintf('insert into video set v_id="%d", u_id="%d", title="%s", v_name="201512081242290.mp4", original_name="question1.mp4", times_p="%d", times_c=0, sam="201512081242290.jpg", category="none", tag="none", created="%s", intro_text="test"', $val1['maxvid'], $uid, $title, $timesp, date('Y-m-d H:i:s'));
    mysql_query($sql2) or die(mysql_error());

  $count++;
  sleep(1);
}

header("Location: index.php?page=1");
exit();

/*$b = mysql_query('select count(*) as count from video');
$a = mysql_fetch_assoc($b);
while ($a['count']>0){
  mysql_query('delete from video');
  $b = mysql_query('select count(*) as count from video');
  $a = mysql_fetch_assoc($b);
}
?>
<br>
<?php
$sql = 'select count(*) from video';
echo mysql_query($sql);
*/?>

