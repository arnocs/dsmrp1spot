<?PHP
// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid
include '/usr/local/bin/dsmrp1spot/p1spot.conf';

$date = new DateTime(date("Y-m-d"));
$date->add(DateInterval::createFromDateString('-1 days'));

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
		<!-- include log data -->
		<pre><h3>Current Day</h3><?PHP echo "\n"; include ("$sbfpath/SBFspotUpload".date("Ymd").".log"); ?><h3>Yesterday</h3><?PHP echo "\n"; include ("$sbfpath/SBFspotUpload".$date->format('Ymd').".log"); ?></pre>
	</td></tr>
	</table>
	</body>
</html>
