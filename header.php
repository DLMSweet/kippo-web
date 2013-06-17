<!DOCTYPE html>
<head>
    <title>Kippo Web stats</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-responsive.min.css" />
    <style type='text/css'>
         .spoiler {
             border:1px solid #ddd;
             padding:3px;
             }
         .spoiler .inner {
             border:1px solid #eee;
             padding:3px;
             margin:3px;
             }
    </style>
    <script type='text/javascript'>
        function showSpoiler(obj) {
            var inner = obj.parentNode.getElementsByTagName('div')[0];
            if (inner.style.display == 'none') {
                inner.style.display = '';7
            } else {
                inner.style.display = 'none';
            }
         } </script>
</head>
<body>
<a href="index.php">Main Page</a><br>
<a href="show.php?id=ips">Show Remote IP addresses</a><br>
<a href="show.php?id=logins">Show Login attempts</a><br>
<a href="show.php?id=Playlogs">Playback sessions</a><br>
<a href="show.php?id=sensorHitRate">Show Sensor Hitrate</a><br>
<a href="show.php?id=textlogs">Show Text logs</a><br><br>
