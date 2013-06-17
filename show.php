<?php
include "header.php";
include "_database.php";
include "_functions.php";

if(isset($_GET["id"])) {
    $toShow=$_GET["id"];
}
if(isset($_GET["ip"])) {
    $ip=$_GET["ip"];
}


if ( $toShow == "ips" ) {
    print_uniqIps();
}
elseif ( $toShow == "logins" ) {
    print_loginAttempts();
}
elseif ( $toShow == "Playlogs" ) {
    print_playlogs();
}
elseif ( $toShow == "Detail" ) {
    detail_ip($ip);
}
elseif ( $toShow =="sensorHitRate" ) {
    printSensorHitCount();
    printSensorLoginCount();
}
elseif ( $toShow =="textlogs" ) {
    print_textLogs(PHP_INT_MAX);
}
else {
    echo "What.";
}
?>
