<?PHP
// Include config file
// *** P1path		* $p1path
// *** INPUT FILE       * $p1latestfile
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
	<!-- PVoutput.org embedded script -->
		<script type='text/javascript' src='http://pvoutput.org/widget/inc.jsp'></script>
		<td align="center"><script type='text/javascript' src='http://pvoutput.org/widget/outputs.jsp?sid=<?PHP echo $pvosid ?>&n=1&c=1'></script></td>
		<td align="center"><script type='text/javascript' src='http://pvoutput.org/widget/graph.jsp?sid=<?PHP echo $pvosid ?>'></script></td>
	</tr>
	<tr><td colspan="3" valign="top">
		<pre>
			<?PHP echo "\n"; include ("$p1path/www/p1all.php"); ?>
		</pre>
	</td></tr>
	</table>
	</body>
</html>
