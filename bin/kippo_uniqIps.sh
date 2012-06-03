#!/bin/bash
echo "<table border=1>"
echo "<tr><td>Login Attempts</td><td>IP Address</td><td>GeoIP</td></tr>"
for LINE in $(mysql kippo -u$1 -p$2 -e "select ip from sessions;" | grep -v ip | sort | uniq -c | sort -nrk1 | awk '{print $1"_"$2}'); 
do
  LOGINATTEMPTS=$(echo $LINE |tr '_' ' ' | awk '{print $1}')
  IPADDRESS=$(echo $LINE |tr '_' ' ' | awk '{print $2}')
  GEOIP=$(geoiplookup $IPADDRESS | sed 's/GeoIP Country Edition: //g')
  echo "<tr><td>"$LOGINATTEMPTS"</td><td><a href=show.php?id=Detail&ip=$IPADDRESS>"$IPADDRESS"</a></td><td>"$GEOIP"</td></tr>"
done 
echo "</table>"

