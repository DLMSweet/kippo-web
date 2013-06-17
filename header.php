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
            $(obj.parentNode.getElementsByTagName('div')[0]).collapse('toggle')
         } </script>
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Title</a>
    <ul class="nav">
    <li><a href="index.php">Main Page</a></li>
    <li><a href="show.php?id=ips">Show Remote IP addresses</a></li>
    <li><a href="show.php?id=logins">Show Login attempts</a></li>
    <li><a href="show.php?id=Playlogs">Playback sessions</a></li>
    <li><a href="show.php?id=sensorHitRate">Show Sensor Hitrate</a></li>
    <li><a href="show.php?id=textlogs">Show Text logs</a></li>
    </ul>
  </div>
</div>
