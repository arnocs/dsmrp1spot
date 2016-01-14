<?PHP
// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid
include '/usr/local/bin/dsmrp1spot/p1spot.conf';
?>

<html>
	<head>
	</head>
	<body>
	<table>
        <tr>
		<td></td>
		<!-- PVoutput.org embedded script -->
                <script type='text/javascript' src='http://pvoutput.org/widget/inc.jsp'></script>
                <td align="center"><script type='text/javascript' src='http://pvoutput.org/widget/outputs.jsp?sid=<?PHP echo $pvosid ?>&n=1&c=1'></script></td>
                <td align="center"><script type='text/javascript' src='http://pvoutput.org/widget/graph.jsp?sid=<?PHP echo $pvosid ?>'></script></td>

        </tr>
	<tr>
		<td valign="top" colspan="2">
			<!-- include P1 poort data captured from serial port every 5 min (see crontab) -->
			<pre>
				<?PHP echo "\n"; include ("$p1path/data/latest.txt"); ?>
			</pre>
		</td>
		<td valign="top" colspan="1">
			<pre>
				<?PHP echo "\n"; include ("$p1path/www/p1telegram.php"); ?>
			</pre>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<pre>
				<?PHP echo "\n"; include ("$p1path/www/p1telegramrecords.php"); ?>
			</pre>
		</td>
	</tr>
	</table>
	</body>
</html>
