#!/bin/bash
tmpfile="/tmp/$$"
mysql kippo -u$1 -p$2 --skip-column-names -Be "SELECT sessions.id,auth.username,auth.password,sessions.starttime,sessions.endtime,auth.success FROM sessions RIGHT JOIN auth ON sessions.id = auth.session WHERE sessions.ip=\"$3\";" | sed 's/\t/,/g' > $tmpfile
echo "<h3>Logins attempted from $3</h3>"
echo "<table border=1>"
echo "<tr><td>Username</td><td>Password</td><td>Success</td></tr>"
while read line;
do
    SessionID=$(echo $line | cut -d ',' -f1)
    Username=$(echo $line | cut -d ',' -f2)
    Password=$(echo $line | cut -d ',' -f3)
    SessionStart=$(echo $line | cut -d ',' -f4)
    SessionEnd=$(echo $line | cut -d ',' -f5)
    Success=$(echo $line | cut -d ',' -f6)
	if [[ $Success -eq "0" ]]
        then
	    echo "<tr><td>$Username</td><td>$Password</td><td>Failure</td></tr>"
	else
	    echo "<tr BGCOLOR=\"#00FF00\"><td>$Username</td><td>$Password</td><td>Success</td></tr>"
	fi
done < $tmpfile
rm -f $tmpfile
