#!/bin/bash
 for FILE in $(ls -l /opt/kippo/log/tty/*.log | awk '$5>2128' | cut -d '/' -f6); do echo \<a href="playlog/?l=$FILE"\>$FILE\</a\>\<br /\>; done
