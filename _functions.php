<?php

function printInput($id) {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="select input from input where session='$id'";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
    while ($line < $num) {
        $input=mysql_result($gotInput,$line,"input");
        echo "<code>$input</code><br>\n";
        $line++;
    }

}

function printSensorHitCount() {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT sensors.ip,COUNT(*) from sessions RIGHT JOIN sensors ON sensors.id = sessions.sensor GROUP BY sensors.ip ORDER BY COUNT(*) DESC;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    $line=0;
    echo "<h3>Hit rate by Sensor</h3>\n";
    echo "<table border=1>\n";
    echo "<tr><td>Sensor Name</td><td>Times Hit</td></tr>\n";
    while ($line < $num) {
        $sensor=mysql_result($gotInput,$line,"sensors.ip");
        $hitcount=mysql_result($gotInput,$line,"COUNT(*)");
        echo "<tr><td>$sensor</td><td>$hitcount</td></tr>\n";
        $line++;
    }
    echo "</table>";
}

function printSensorLoginCount() {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT sensors.ip,COUNT(sessions.ip) as logins FROM sessions LEFT JOIN auth ON auth.session = sessions.id LEFT JOIN sensors ON sessions.sensor = sensors.id WHERE auth.success=1 GROUP BY sensors.ip ORDER BY COUNT(sessions.ip) DESC;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
    echo "<h3>Times logged into by Sensor</h3>\n";
    echo "<table border=1>\n";
    echo "<tr><td>Sensor Name</td><td>Times logged into</td></tr>\n";
    while ($line < $num) {
        $sensor=mysql_result($gotInput,$line,"sensors.ip");
        $hitcount=mysql_result($gotInput,$line,"logins");
        echo "<tr><td>$sensor</td><td>$hitcount</td></tr>\n";
        $line++;
    }
    echo "</table>";
}

function print_uniqIps() {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="select ip,COUNT(*) from sessions GROUP BY sessions.ip ORDER BY COUNT(*) DESC;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
    echo "<table border=1>\n";
    echo "<tr><td>Login Attempts</td><td>IP Address</td><td>GeoIP</td></tr>\n";
    while ($line < $num) {
        $ip=mysql_result($gotInput,$line,"ip");
        $hitcount=mysql_result($gotInput,$line,"COUNT(*)");
		$geoIP=geoip_country_name_by_name($ip);
        echo "<tr><td>$hitcount</td><td><a href=show.php?id=Detail&ip=$ip>$ip</a></td><td>$geoIP</td></tr>\n";
        $line++;
    }
}

function print_loginAttempts() {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT username,password,COUNT(*) FROM auth GROUP BY username,password ORDER BY COUNT(*) DESC ;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
    echo "<table border=1>\n";
    echo "<tr><td>Times Used</td><td>Username</td><td>Password</td></tr>\n";
    while ($line < $num) {
        $timesTried=mysql_result($gotInput,$line,"COUNT(*)");
        $AtmptUsername=mysql_result($gotInput,$line,"username");
		$AtmptPassword=mysql_result($gotInput,$line,"password");
        echo "<tr><td>$timesTried</td><td>$AtmptUsername</td><td>$AtmptPassword</td></tr>\n";
        $line++;
    }
}

function getWhois($ip) {
	$queryInput = $ip;
	// create a new cURL resource
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, 'http://whois.arin.net/rest/ip/' . $queryInput);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

	// execute
	$returnValue = curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

	$result = json_decode($returnValue);

        echo "<pre>";
	echo "Handle: {$result->net->handle->{'$'}}<br />";
	echo "Ref: {$result->net->ref->{'$'}}<br />";
	echo "Name: {$result->net->name->{'$'}}<br />";
	echo "OrgRef: {$result->net->orgRef->{'@name'}}<br />";
	echo "</pre>";
}

function detail_ip($ip) {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT auth.username,auth.password,sessions.starttime,auth.success FROM sessions RIGHT JOIN auth ON sessions.id = auth.session WHERE sessions.ip='$ip' ORDER BY sessions.starttime;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
//    getWhois($ip);
    echo "<h3>Logins attempted from $ip</h3>";
    echo "<table border=1>\n";
    echo "<tr><td>Username</td><td>Password</td><td>Time Tried</td><td>Success</td></tr>\n";
    while ($line < $num) {
        $timeTried=mysql_result($gotInput,$line,"starttime");
        $AtmptUsername=mysql_result($gotInput,$line,"username");
	$AtmptPassword=mysql_result($gotInput,$line,"password");
	$successful=mysql_result($gotInput,$line,"success");
	if ($successful=='1')
            echo "<tr BGCOLOR=\"#00FF00\"><td>$AtmptUsername</td><td>$AtmptPassword</td><td>$timeTried</td><td>Success</td></tr>";
	else
	    echo "<tr><td>$AtmptUsername</td><td>$AtmptPassword</td><td>$timeTried</td><td>Failure</td></tr>";
        $line++;
    }
}

function print_playlogs() {
    $ttyDir="/opt/kippo/log/tty/";
    // open this directory 
    $myDirectory = opendir($ttyDir);
    // get each entry
    while($entryName = readdir($myDirectory)) {
        $dirArray[] = $entryName;
    }
    // close directory
    closedir($myDirectory);
    //	count elements in array
    sort($dirArray);
    $indexCount	= count($dirArray);
    // print 'em
    print("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
    print("<TR><TH>Filename</TH><th>Filesize</th></TR>\n");
    // loop through the array of files and print them all
    for($index=0; $index < $indexCount; $index++) {
            if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
                if (filesize("$ttyDir$dirArray[$index]")>1000) { //Only print files larger than 1000 bytes
                print("<TR><TD><a href=\"playlog/?l=$dirArray[$index]\">$dirArray[$index]</a></td>");
                print("<td>");
                print(filesize("$ttyDir$dirArray[$index]"));
                print("</td>");
                print("</TR>\n");
            }
        }
    }
    print("</TABLE>\n");
}

function print_textLogs($count) {
        include "_database.php";
	mysql_connect($server,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	$query = "SELECT sessions.id,sensors.ip AS sensor_ip,sessions.ip AS session_IP,starttime,endtime FROM sessions LEFT JOIN auth ON sessions.id=auth.session LEFT JOIN sensors ON sensors.id=sessions.sensor WHERE success=1 ORDER BY starttime DESC;";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	mysql_close();
        
        echo "<h3>Text-based logs of successful logins</h3>";
	echo "<table class=\"table\">\n";
	echo "<tr><td><b>Session ID</b></td><td><b>Sensor</b></td><td><b>IP</b></td><td><b>Start Time</b></td><td><b>End Time</b></td><td><b>Commands Run</b></td></tr>\n";
	$i=0;
	while ($i < $num and $i < $count) {
		$id=mysql_result($result,$i,"id");
		$sensor=mysql_result($result,$i,"sensor_ip");
		$ip=mysql_result($result,$i,"session_IP");
		$starttime=mysql_result($result,$i,"starttime");
		$endtime=mysql_result($result,$i,"endtime");

		mysql_connect($server,$username,$password);
		@mysql_select_db($database) or die( "Unable to select database");
		$input_count_query = "select input from input where session='$id'";
		$input_count=mysql_numrows(mysql_query($input_count_query));

		mysql_close();
		if ( $input_count > 0 ) {
			echo "<tr><td>$id</td><td>$sensor</td><td>$ip</td><td>$starttime</td><td>$endtime</td><td>$input_count</td></tr>\n\n";
			echo "<tr><td colspan=6>\n<div class='spoiler'>\n<input type='button' onclick='showSpoiler(this);' value='Show Commands run' />\n<div class=\"inner collapse\">\n";
			printInput($id);
			echo "</div>\n</div>\n</td></tr>\n";
		}
                else {
                        $count++;
                }
        $i++;
	}
	echo "</table>";
}

function recentAttacks() {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT DISTINCT(sessions.ip) AS remote_ip,sensors.ip AS sensor FROM sessions LEFT JOIN sensors ON sessions.sensor=sensors.id WHERE sessions.starttime>NOW() - INTERVAL 15 MINUTE;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
    echo "<h3>Recent Attacks (last 15 Minutes)</h3>";
    echo "<table class=\"table\">\n";
    echo "<tr><td>Login Attempts</td><td>IP Address</td><td>GeoIP</td></tr>\n";
    while ($line < $num) {
        $remote_ip=mysql_result($gotInput,$line,"remote_ip");
        $sensor=mysql_result($gotInput,$line,"sensor");
        $geoIP=geoip_country_name_by_name($remote_ip);
        echo "<tr><td>$sensor</td><td><a href=show.php?id=Detail&ip=$remote_ip>$remote_ip</a></td><td>$geoIP</td></tr>\n";
        $line++;
    }
    echo "</table>";
}
?>
