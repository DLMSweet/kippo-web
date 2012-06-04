<?php
echo "<!DOCTYPE html>\n";
echo "<head>\n";
echo "    <title>Kippo Web stats</title>\n";
echo "    <style type='text/css'> .spoiler { border:1px solid #ddd; padding:3px; } .spoiler .inner { border:1px solid #eee; padding:3px;margin:3px; } </style>\n";
echo "    <script type='text/javascript'> function showSpoiler(obj) { var inner = obj.parentNode.getElementsByTagName('div')[0]; if (inner.style.display == 'none') inner.style.display = ''; else inner.style.display = 'none'; } </script>\n";
echo "</head>\n";
echo "<body>\n";
echo "<a href='index.php'>Main Page</a><br>\n";
echo "<a href='show.php?id=ips'>Show Remote IP addresses</a><br>\n";
echo "<a href='show.php?id=logins'>Show Login attempts</a><br>\n";
echo "<a href='show.php?id=Playlogs'>Playback sessions</a><br>\n";
echo "<a href='show.php?id=sensorHitRate'>Show Sensor Hitrate</a><br><br>\n";

?>
