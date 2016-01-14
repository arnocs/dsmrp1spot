#!/bin/bash
#
# Reads datagram from P1 port (serial) via pythonscript
# When the output do match the # lenght it reruns
# Else it starts php script that parses and writes it to a database.

p1spotpath=/usr/local/bin/dsmrp1spot
#
p1latestfile=$p1spotpath/data/latest.txt
p1telegramlen=36
#
pybin=/usr/bin/python
pyscript=$p1spotpath/p1spot.py
#
phpbin=/usr/bin/php
phpscript=$p1spotpath/p1spotwrite.php

### Run python script
$pybin $pyscript > $p1latestfile

### check # lines
p1latestlen=$(wc -l $p1latestfile | awk '{ print $1}')
echo $p1latestlen

### if p1telegram is too short rerun this script
if ((p1latestlen<p1telegramlen)); then 
	/bin/bash $0; 
else
	$phpbin $phpscript
fi
echo $p1latestlen

cat $p1latestfile
