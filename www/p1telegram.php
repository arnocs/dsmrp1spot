<?PHP
// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid
include '/usr/local/bin/dsmrp1spot/p1spot.conf';

### Get data from file
#
$handle = fopen($p1latestfile, "r");
	if ($handle) {
		$count=0;
		$output=array();
		while (($line = fgets($handle)) !== false) {
			$count=$count+1;
			$search = array (')','*kWh',"*kW","*m3","*A");   // replace with "" 
			$search2 = array (':','(');                      // replace with ","

			$data[$count]=  str_replace($search2,",", str_replace ($search,"", $line));
			$output[$count]= explode(",", $data[$count]);
		}
	if ($count <> "36") echo "Error p1telegram";
fclose($handle);


### Parse variables from p1 telegram ###
#
$p1parsedstamp = date_parse_from_format("ymdHis", substr(trim($output[4][2]), 0, -1));
$p1timestamp = mktime( $p1parsedstamp['hour'],$p1parsedstamp['minute'],$p1parsedstamp['second'],$p1parsedstamp['month'],$p1parsedstamp['day'],$p1parsedstamp['year']);
$p1timestamp = date('Y-m-d H:i:s', $p1timestamp);

$p1header = trim($output[1][0]);
$p1version = intval($output[3][2]);					// ### Version
$p1serialkwh = trim($output[5][2]);					// ### Serial number kWh

$p1energyimportpeakoff = floatval($output[6][2]);			// ### Import offpeak
$p1energyimportpeak = floatval($output[7][2]);				// ### Import peak
$p1eneryimport = $p1energyimportpeakoff+$p1energyimportpeak;		// ### Energy Import Total calculated
$p1energyexportpeakoff = floatval($output[8][2]);			// ### Export offpeak
$p1energyexportpeak = floatval($output[9][2]);				// ### Export peak
$p1eneryexport = $p1energyexportpeakoff+$p1energyexportpeak;		// ### Energy Export Total calculated

$p1tariff = intval($output[10][2]);					// ### Tariff indicator

$p1powerimport = floatval($output[11][2]);				// ### Actual Import
$p1powerexport = floatval($output[12][2]);				// ### Actual Export

$p1failureany = intval($output[13][2]);					// ### Power failures, in any phase
$p1failurelong = intval($output[14][2]);				// ### Power failures, number of long
$p1failurelogtimes = intval($output[15][2]);				// ### Power failures, event log times
$p1failurelog = trim($output[15][3]);					// ### Power failures, event log
$p1failurelog.= ",".trim($output[15][4]);
$p1failurelog.= ",".trim($output[15][5]);
$p1failurelog.= ",".trim($output[15][6]);

$p1voltagesagl1 = intval($output[16][2]);				// ### Voltage sags L1
$p1voltagesagl2 = intval($output[17][2]);				// ### Voltage sags L2
$p1voltagesagl3 = intval($output[18][2]);				// ### Voltage sags L3
$p1voltagesags = $p1voltagesagl1+$p1voltagesagl2+$p1voltagesagl3;

$p1voltageswelll1 = intval($output[19][2]);				// ### Voltage swells L1
$p1voltageswelll2 = intval($output[20][2]);				// ### Voltage swells L2
$p1voltageswelll3 = intval($output[21][2]);				// ### Voltage swells L3
$p1voltageswells = $p1voltageswelll1+$p1voltageswelll2+$p1voltageswelll3;

$p1textmessage = trim($output[22][2]);					// ### Text message
$p1unknown = trim($output[23][2]);					// ### [unkown]

$p1currentl1 = intval($output[24][2]);					// ### L1, Actual current
$p1currentl2 = intval($output[25][2]);					// ### L2, Actual current
$p1currentl3 = intval($output[26][2]);					// ### L3, Actual current
$p1current = $p1currentl1+$p1currentl2+$p1currentl3;			// ### Actual current Total

$p1powerimportl1 = floatval($output[27][2]);				// ### L1, Actual power +P Import
$p1powerimportl2 = floatval($output[29][2]);				// ### L2, Actual power +P Import
$p1powerimportl3 = floatval($output[31][2]);				// ### L3, Actual power +P Import
$p1powerexportl1 = floatval($output[28][2]);				// ### L1, Actual power -P Export
$p1powerexportl2 = floatval($output[30][2]);				// ### L2, Actual power -P Export
$p1powerexportl3 = floatval($output[32][2]);				// ### L3, Actual power -P Export

$p1powerimport = $p1powerimportl1+$p1powerimportl2+$p1powerimportl3;	// ### Actual power Import
$p1powerexport = $p1powerexportl1+$p1powerexportl2+$p1powerexportl3;	// ### Actual power Export

$p1devicetypegasm3 = intval($output[33][2]);				// ### Device type
$p1serialgasm3 = trim($output[34][2]);					// ### Serial number Gas m^3
$p1gasm3 = floatval($output[35][3]);					// ### Gas m^3
// $p1timestampgasm3 = rtrim($output[35][2]);                           // ### Timestamp Gas m^3
$p1parsedstamp = date_parse_from_format("ymdHis", substr(trim($output[35][2]), 0, -1));
$p1timestampgasm3 = mktime( $p1parsedstamp['hour'],$p1parsedstamp['minute'],$p1parsedstamp['second'],$p1parsedstamp['month'],$p1parsedstamp['day'],$p1parsedstamp['year']);
$p1timestampgasm3 = date('Y-m-d H:i:s', $p1timestampgasm3);
$p1tail = trim($output[36][0]);




### Test
#
// ### TEST 
// $output = exec('/usr/bin/python /usr/local/bin/p1spot/p1spot.py');
// shell_exec('/usr/bin/python /usr/local/bin/p1spot/p1spot.py > /home/pi/p1data/latest.txt');


// echo "- mysql - " . date('Y-m-d H:i:s', $p1timestamp) . "\n";
// echo "-" . date('U', date('Y-m-d H:i:s', $p1timestamp)) . "\n";

/*
echo "\n";
$p1timestamp=date('Y-m-d H:i:s', $p1timestamp);
if (($timestamp = strtotime($p1timestamp)) === false) {
    echo "The string ($p1timestamp) is bogus";
} else {
    echo "$p1timestamp == " . date('l dS \o\f F Y h:i:s A', $timestamp) . "\n";
};
echo "\n";
*/

// $timestamp = strtotime($p1timestamp);
// echo date("Y-m-d H:i:s", $timestamp);

// echo "\n-- ". strtotime($p1timestamp);
//echo "\n-- ". date("Y-m-d H:i:s", strtotime("now"));
// echo "\n-- ". date("Y-m-d H:i:s", strtotime($p1timestamp));


// echo strtotime($p1timestamp);
// echo strtotime("now");

// echo date_format($p1timestamp, 'u'); 


// $date = DateTime::createFromFormat('!d-m-Y', '22-09-2008');

// echo $dateTime->format('U');

// echo intval($output[3][2]) .",". $p1timestamp;  

//echo "\n---------------\n";
//echo "-- ". date("Y-m-d H:i:s", strtotime("now"));
//echo " --\n";

### Old p1 telegram
#
$count=1;
while ( substr($output[$count][0],0,1) <> "!" ) {
//	echo $count . " ]";
	if ($count == "1") echo $output[$count][0];
	$count=$count+1;

	if ($output[$count][1] == "0.2.8") echo "DSRM\t\t\t\tv" . intval($output[$count][2]) . "\n";				// 3
	if ($output[$count][1] == "1.0.0") echo "Timestamp YYMMDDhhmmssX\t\t" . trim($output[$count][2]) . "\n";		// 4
	if ($output[$count][1] == "96.1.1") echo "Serial kWh\t\t\t" . trim($output[$count][2]) . "\n";				// 5

	if ($output[$count][1] == "1.8.1") echo "Import offpeak\t\t\t" . floatval($output[$count][2]) . "\t kWh" . "\n";        // 
	if ($output[$count][1] == "1.8.2") echo "Import    peak\t\t\t" . floatval($output[$count][2]) . "\t kWh" . "\n";
	if ($output[$count][1] == "2.8.1") echo "Export offpeak\t\t\t" . floatval($output[$count][2]) . "\t kWh" . "\n";
	if ($output[$count][1] == "2.8.2") echo "Export    peak\t\t\t" . floatval($output[$count][2]) . "\t kWh" . "\n";

	if ($output[$count][1] == "96.14.0") echo "Tariff indicator\t\t" . floatval($output[$count][2]) . "\t. " . "\n";

	if ($output[$count][1] == "1.7.0") echo "Actual Import\t\t\t" . floatval($output[$count][2]) . "\t kW" . "\n";
	if ($output[$count][1] == "2.7.0") echo "Actual Export\t\t\t" . floatval($output[$count][2]) . "\t kW" . "\n";

	if ($output[$count][1] == "96.7.21") echo "Power failures, in any phase\t" . intval($output[$count][2]) . " Times" . "\n";
	if ($output[$count][1] == "96.7.9") echo "Power failures, number of long\t" . intval($output[$count][2]) . " Times" . "\n";
	if ($output[$count][1] == "99.97.0") echo "Power failures, event log\t" . intval($output[$count][2]) . "," . trim($output[$count][3]) . "," . trim($output[$count][4]) . "," . trim($output[$count][5]) . "," . trim($output[$count][6]) . " " . "\n";


	if ($output[$count][1] == "32.32.0") echo "L1, # voltage sags\t\t" . intval($output[$count][2]) . "\n";
	if ($output[$count][1] == "52.32.0") echo "L2, # voltage sags\t\t" . intval($output[$count][2]) . "\n";
	if ($output[$count][1] == "72.32.0") echo "L3, # voltage sags\t\t" . intval($output[$count][2]) . "\n";

        if ($output[$count][1] == "32.36.0") echo "L1, # voltage swells\t\t" . intval($output[$count][2]) . "\n";
        if ($output[$count][1] == "52.36.0") echo "L2, # voltage swells\t\t" . intval($output[$count][2]) . "\n";
        if ($output[$count][1] == "72.36.0") echo "L3, # voltage swells\t\t" . intval($output[$count][2]) . "\n";

	if ($output[$count][1] == "96.13.1") echo "Text message\t\t\t" . trim($output[$count][2]) . "\t  .\n";
	if ($output[$count][1] == "96.13.0") echo "[unkown]\t\t\t" . trim($output[$count][2]) . "\t  .\n";

	if ($output[$count][1] == "31.7.0") echo "L1, Actual current\t\t" . intval($output[$count][2]) . "\t  A\n";
        if ($output[$count][1] == "51.7.0") echo "L2, Actual current\t\t" . intval($output[$count][2]) . "\t  A\n";
        if ($output[$count][1] == "71.7.0") echo "L3, Actual current\t\t" . intval($output[$count][2]) . "\t  A\n";

	if ($output[$count][1] == "21.7.0") echo "L1, Actual power +P\t\t" . floatval($output[$count][2]) . "\t  kW\n";
	if ($output[$count][1] == "41.7.0") echo "L2, Actual power +P\t\t" . floatval($output[$count][2]) . "\t  kW\n";
	if ($output[$count][1] == "61.7.0") echo "L3, Actual power +P\t\t" . floatval($output[$count][2]) . "\t  kW\n";

	if ($output[$count][1] == "22.7.0") echo "L1, Actual power -P\t\t" . floatval($output[$count][2]) . "\t  kW\n";
	if ($output[$count][1] == "42.7.0") echo "L2, Actual power -P\t\t" . floatval($output[$count][2]) . "\t  kW\n";
	if ($output[$count][1] == "62.7.0") echo "L3, Actual power -P\t\t" . floatval($output[$count][2]) . "\t  kW\n";

	if ($output[$count][1] == "24.1.0") echo "Device type\t\t\t" . intval($output[$count][2]) . "\t  .\n";
	if ($output[$count][1] == "96.1.0") echo "Serial  m³ Gas\t\t\t" . trim($output[$count][2]) . "\n";
	if ($output[$count][1] == "24.2.1") echo "Gas=\t\t\t\t" . floatval($output[$count][3]) . "\t  m³" . " \ntimestamp YYMMDDhhxxxxS \t" . rtrim($output[$count][2]) . "\n";
}

echo "---------------\n";
} else {
    // error opening the file.
} 

?>
