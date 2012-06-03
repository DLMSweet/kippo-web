#!/bin/bash
echo "<table border=1>"
echo "<tr><td>Times Used</td><td>Username</td><td>Password</td></tr>"
mysql kippo -u$1 -p$2 --skip-column-names -Be "SELECT username,password FROM auth;" | sort -k1 | uniq -c | sort -nrk1 | awk '{print "<tr><td>"$1"</td><td>"$2"</td><td>"$3"</td></tr>"}'
echo "</table>"
