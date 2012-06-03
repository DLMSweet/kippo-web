<?php
include "_database.php";
include "_functions.php";
include "header.php";

mysql_connect($server,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
//$query = "select sessions.id,sensor,ip,starttime,endtime FROM sessions LEFT JOIN auth ON sessions.id=auth.session WHERE ( SELECT TIMEDIFF(endtime, starttime)>60 AND success=1)";
$query = "select sessions.id,sensor,ip,starttime,endtime FROM sessions LEFT JOIN auth ON sessions.id=auth.session WHERE success=1";
$result=mysql_query($query);
$num=mysql_numrows($result);

mysql_close();
echo "<table border=1>\n";
echo "<tr><td><b>Session ID</b></td><td><b>Sensor</b></td><td><b>IP</b></td><td><b>Start Time</b></td><td><b>End Time</b></td><td><b>Commands Run</b></td></tr>\n";

$i=0;
while ($i < $num) {
    $id=mysql_result($result,$i,"id");
    $sensor_id=mysql_result($result,$i,"sensor");
    $ip=mysql_result($result,$i,"ip");
    $starttime=mysql_result($result,$i,"starttime");
    $endtime=mysql_result($result,$i,"endtime");

    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $input_count_query = "select input from input where session='$id'";
    $input_count=mysql_numrows(mysql_query($input_count_query));

    $query_getSensor = "select ip from sensors where id='$sensor_id'";
    $result_getSensor = mysql_query($query_getSensor);
    $sensor = mysql_result($result_getSensor,0,"ip");

    mysql_close();
    if ( $input_count > 0 ) {
        echo "<tr><td>$id</td><td>$sensor</td><td>$ip</td><td>$starttime</td><td>$endtime</td><td>$input_count</td></tr>\n\n";
        echo "<tr><td colspan=6>\n<div class='spoiler'>\n<input type='button' onclick='showSpoiler(this);' value='Show Commands run' />\n<div class='inner' style='display:none;'>\n";
        printInput($id);
        echo "</div>\n</div>\n</td></tr>\n";
    }

    $i++;
}
echo "</table>";

?>
