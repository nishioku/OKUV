<?php
require('dbconnect.php');
session_start();

if (!empty($_REQUEST)) {
        $sql1 = 'select count(*) as cnt from video';
	//$sql2 = 'mysql_real_escape_string($comment)';
        //$sql3 = 'mysql_real_escape_string($_GET["vid"])';
        $recordSet = mysql_query($sql1);
        $table = mysql_fetch_assoc($recordSet);
        $count = ceil($table['cnt']);
        //登録処理をする
        $sql = sprintf('insert into comment set c_id="%d", comment="%s", v_id="%s", created="%s"',
            $count, mysql_real_escape_string($_REQUEST['comment']), mysql_real_escape_string($_GET['vid']), date('Y-m-d H:i:s'));
        mysql_query($sql) or die(mysql_error());
        //unset($_SESSION['join']);
}

?>
