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
        echo "$input<br>\n";
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
    mysql_close();
    $line=0;
    echo "<table border=1>\n";
    echo "<tr><td>Sensor Name</td><td>Times Hit</td></tr>\n";
    while ($line < $num) {
        $sensor=mysql_result($gotInput,$line,"sensors.ip");
        $hitcount=mysql_result($gotInput,$line,"COUNT(*)");
        echo "<tr><td>$sensor</td><td>$hitcount</td></tr>\n";
        $line++;
    }
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
    $query_getInput="SELECT username,password,COUNT(*) FROM auth GROUP BY username,password ORDER BY COUNT(*) DESC;";
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

function detail_ip($ip) {
    include "_database.php";
    mysql_connect($server,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    $query_getInput="SELECT auth.username,auth.password,sessions.starttime,auth.success FROM sessions RIGHT JOIN auth ON sessions.id = auth.session WHERE sessions.ip='$ip' ORDER BY sessions.starttime;";
    $gotInput=mysql_query($query_getInput);
    $num=mysql_numrows($gotInput);
    mysql_close();
    $line=0;
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

?>
