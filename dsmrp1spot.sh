#!/bin/bash
#
# Reads datagram from P1 port (serial) via pythonscript
# When the output do match the # lenght it reruns
# Else it starts php script that parses and writes it to a database.

### Definitions
p1spotpath=/usr/local/bin/dsmrp1spot
p1latestfile=$p1spotpath/data/latest.txt
p1telegramlen=36

### PHP
phpbin=/usr/bin/php
phpscript=$p1spotpath/dsmrp1spot-write.php

### Python
pybin=/usr/bin/python
pyscript=$p1spotpath/dsmrp1spot-read.py


#####################################################
### Main

### Run python script, get data from serial port.
$pybin $pyscript > $p1latestfile

### check length of file (# lines)
p1latestlen=$(wc -l $p1latestfile | awk '{ print $1}')
echo $p1latestlen

### if p1telegram is too short re-run this script.
if ((p1latestlen<p1telegramlen)); then 
	/bin/bash $0; 
else
	$phpbin $phpscript
fi

### DEBUG
# echo $p1latestlen
cat $p1latestfile
