<?PHP
// Rev 2

// Get data from file and write
// converts raw p1-port telegram to CSV format and extract data to variables

// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid

include '/usr/local/bin/dsmrp1spot/p1spot.conf';

// ### Get data from p1-telegram file and convert it to CSV
$handle = fopen($p1latestfile, "r");
	if ($handle) {
		$count=0;
		$output=array();
		while (($line = fgets($handle)) !== false) {
			$count=$count+1;

			// 
			$search = array (')','*kWh',"*kW","*m3","*A");	// to replace with "" 
			$search2 = array (':','(');			// to replace with ","

			// replace with "," and ""
			$data[$count]=  str_replace($search2,",", str_replace ($search,"", $line));

			// convert to array
			$output[$count]= explode(",", $data[$count]);
		}
	if ($count <> $p1length) echo "Error p1 telegram";
fclose($handle);
}

// ### Parse variables from p1 telegram ###
$p1parsedstamp = date_parse_from_format("ymdHis", substr(trim($output[4][2]), 0, -1));
$p1timestamp = mktime( $p1parsedstamp['hour'],$p1parsedstamp['minute'],$p1parsedstamp['second'],$p1parsedstamp['month'],$p1parsedstamp['day'],$p1parsedstamp['year']);
$p1timestamp = date('Y-m-d H:i:s', $p1timestamp);			// ### TimpStamp MySQL
$p1timestampunix = strtotime($p1timestamp);				// ### TimeStamp Unix
$p1time = date('H:i', $p1timestampunix);
$p1date = date('Ymd', $p1timestampunix);

$p1head = trim($output[1][0]);						// ### Header text
$p1version = intval($output[3][2]);					// ### Version
$p1serialkwh = trim($output[5][2]);					// ### Serial number kWh

$p1energyimportpeakoff = 1000 * floatval($output[6][2]);		// ### Import offpeak
$p1energyimportpeak = 1000 * floatval($output[7][2]);			// ### Import peak
$p1energyimport = $p1energyimportpeakoff+$p1energyimportpeak;		// #C# Energy Import Total calculated
$p1energyexportpeakoff = 1000 * floatval($output[8][2]);		// ### Export offpeak
$p1energyexportpeak = 1000 * floatval($output[9][2]);			// ### Export peak
$p1eneryexport = $p1energyexportpeakoff+$p1energyexportpeak;		// #C# Energy Export Total calculated

$UsedEnergy = $p1eneryexport+$p1energyimport;				// #C# Used Energy

$p1tariff = intval($output[10][2]);					// ### Tariff indicator

$p1pimport = 1000 * floatval($output[11][2]);				// ### Actual Import
$p1pexport = 1000 * floatval($output[12][2]);				// ### Actual Export

$p1failureany = intval($output[13][2]);					// ### Power failures, in any phase
$p1failurelong = intval($output[14][2]);				// ### Power failures, number of long
$p1failurelogevent = intval($output[15][2]);				// ### Power failures, event id
$p1failurelogevent1 = trim($output[15][3]);				// ### Power failures, event OBIS1
$p1failurelogevent2 = trim($output[15][4]);				// ### Power failures, event OBIS2
$p1failurelogeventcode = substr(trim($output[15][5]),0,-1);		// ### Power failures, event Code
$p1failurelogeventduration = substr(trim($output[15][6]),0,-2);		// ### Power failures, event duration

$p1voltagesag1 = intval($output[16][2]);				// ### Voltage sags L1
$p1voltagesag2 = intval($output[17][2]);				// ### Voltage sags L2
$p1voltagesag3 = intval($output[18][2]);				// ### Voltage sags L3
$p1voltagesag = $p1voltagesag1+$p1voltagesag2+$p1voltagesag3;		// #C# Total

$p1voltageswell1 = intval($output[19][2]);				// ### Voltage swells L1
$p1voltageswell2 = intval($output[20][2]);				// ### Voltage swells L2
$p1voltageswell3 = intval($output[21][2]);				// ### Voltage swells L3
$p1voltageswell = $p1voltageswell1+$p1voltageswell2+$p1voltageswell3;	// #C# Total

$p1textmessage = trim($output[22][2]);					// ### Text message
$p1unknown = trim($output[23][2]);					// ### [unkown]

$p1currentl1 = intval($output[24][2]);					// ### L1, Actual current
$p1currentl2 = intval($output[25][2]);					// ### L2, Actual current
$p1currentl3 = intval($output[26][2]);					// ### L3, Actual current
$p1current = $p1currentl1+$p1currentl2+$p1currentl3;			// #C# Actual current Total

$p1powerimportl1 = 1000 * floatval($output[27][2]);			// ### L1, Actual power +P Import
$p1powerimportl2 = 1000 * floatval($output[29][2]);			// ### L2, Actual power +P Import
$p1powerimportl3 = 1000 * floatval($output[31][2]);			// ### L3, Actual power +P Import
$p1powerimport = $p1powerimportl1+$p1powerimportl2+$p1powerimportl3;	// #C# Actual power Import

$p1powerexportl1 = 1000 * floatval($output[28][2]);			// ### L1, Actual power -P Export
$p1powerexportl2 = 1000 * floatval($output[30][2]);			// ### L2, Actual power -P Export
$p1powerexportl3 = 1000 * floatval($output[32][2]);			// ### L3, Actual power -P Export
$p1powerexport = $p1powerexportl1+$p1powerexportl2+$p1powerexportl3;	// #C# Actual power Export

$p1UsedPower  = $p1powerexport-$p1pexport+$p1powerimport; // PacE-Pexport+PacI

$p1gasm3devicetype = intval($output[33][2]);				// ### Device type
$p1gasm3serial = trim($output[34][2]);					// ### Serial number Gas m^3
$p1gasm3 = floatval($output[35][3]);					// ### Gas m^3
// $p1timestampgasm3 = rtrim($output[35][2]);                           // ### Timestamp Gas m^3 (NotParsed)

									// ### Parse Gas TimeStamp
$p1parsedstamp = date_parse_from_format("ymdHis", substr(trim($output[35][2]), 0, -1));
$p1timestampgasm3 = mktime( $p1parsedstamp['hour'],$p1parsedstamp['minute'],$p1parsedstamp['second'],$p1parsedstamp['month'],$p1parsedstamp['day'],$p1parsedstamp['year']);
$p1timestampgasm3 = date('Y-m-d H:i:s', $p1timestampgasm3);		// ### TimeStamp MySQL
$p1timestampgasm3unix = strtotime($p1timestampgasm3);			// ### TimeStamp Unix

$p1tail = trim($output[36][0]);						// ### Tail text

// **********************************************************
// P1spot data
//
// Create connection
$conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

// READ *** Query read data, get last record
// ***
$sqlread = "SELECT ";
// ***
$sqlread.= "TimeStamp,TimeStampmysql,";
$sqlread.= "Energyimport,EnergyimportPeakOff,EnergyimportPeak,";
$sqlread.= "Energyexport,EnergyexportPeakOff,EnergyexportPeak,";
$sqlread.= "EnergyimportDaily,EnergyexportDaily";
// ***
$sqlread.= " FROM P1device ORDER BY TimeStamp DESC LIMIT 1;";

$result = $conn->query($sqlread);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$lastp1timestampunix = $row["TimeStamp"];
		$lastp1timestamp = $row["TimeStampmysql"];
		$lastp1energyimport = $row["Energyimport"];
		$lastp1energyimportpeakoff = $row["EnergyimportPeakOff"];
		$lastp1energyimportpeak = $row["EnergyimportPeak"];
		$lastp1eneryexport = $row["Energyexport"];
		$lastp1energyexportpeakoff = $row["EnergyexportPeakOff"];
		$lastp1energyexportpeak = $row["EnergyexportPeak"];
		$lastEnergyimportDaily = $row["EnergyimportDaily"];
		$lastEnergyexportDaily = $row["EnergyexportDaily"];
	} 
} else { 
	echo "0 results"; 
} 

	$EnergyimportDelta = intval($p1energyimport) - intval($lastp1energyimport);
	$EnergyimportPeakOffDelta = intval($p1energyimportpeakoff) - intval($lastp1energyimportpeakoff);
	$EnergyimportPeakDelta = intval($p1energyimportpeak) - intval($lastp1energyimportpeak);

	$EnergyexportDelta = intval($p1eneryexport) - intval($lastp1eneryexport);
	$EnergyexportPeakOffDelta = intval($p1energyexportpeakoff) - intval($lastp1energyexportpeakoff);
	$EnergyexportPeakDelta = intval($p1energyexportpeak) - intval($lastp1energyexportpeak);

	$EnergyimportDaily = $lastEnergyimportDaily + $EnergyimportDelta ;
	$EnergyexportDaily = $lastEnergyexportDaily + $EnergyexportDelta ;

	// Required for pvoutput, consumption is cummulative daily
	if ($p1time=="00:00") { $EnergyimportDaily = $lastEnergyimportDaily;}
	if ($p1time=="00:00") { $EnergyexportDaily = $lastEnergyexportDaily;}

		
// INSERT *** Query
// ***
$sql = "INSERT INTO P1device (";
// ***
$sql.= "Serial,SW_Version,TimeStamp,TimeStampmysql,Head,Tail,Tariff,Message,Unknown,";
$sql.= "Energyimport,EnergyimportPeakOff,EnergyimportPeak,";
$sql.= "Energyexport,EnergyexportPeakOff,EnergyexportPeak,";
$sql.= "Pimport,PacI,PacI1,PacI2,PacI3,Pexport,PacE,PacE1,PacE2,PacE3,";
$sql.= "Iac,Iac1,Iac2,Iac3,";
$sql.= "Failureany,Failurelong,Failevent,Failevent1,Failevent2,Faileventcode,Faileventduration,";
$sql.= "Usag,Usag1,Usag2,Usag3,Uswell,Uswell1,Uswell2,Uswell3,";
$sql.= "Gasm3DeviceType,Gasm3Serial,Gasm3timestamp,Gasm3timestampmysql,Gasm3,";
$sql.= "EnergyimportDelta,EnergyimportPeakOffDelta,EnergyimportPeakDelta,";
$sql.= "EnergyexportDelta,EnergyexportPeakOffDelta,EnergyexportPeakDelta,";
$sql.= "EnergyimportDaily,EnergyexportDaily";


// ***
$sql.= ") VALUES (";
$sql.= "'$p1serialkwh','$p1version','$p1timestampunix','$p1timestamp','$p1head','$p1tail','$p1tariff','$p1textmessage','$p1unknown',";
$sql.= "'$p1energyimport','$p1energyimportpeakoff','$p1energyimportpeak','$p1eneryexport','$p1energyexportpeakoff','$p1energyexportpeak',";
$sql.= "'$p1pimport','$p1powerimport','$p1powerimportl1','$p1powerimportl2','$p1powerimportl3','$p1pexport','$p1powerexport','$p1powerexportl1','$p1powerexportl2','$p1powerexportl3',";
$sql.= "'$p1current','$p1currentl1','$p1currentl2','$p1currentl3',";
$sql.= "'$p1failureany','$p1failurelong','$p1failurelogevent','$p1failurelogevent1','$p1failurelogevent2','$p1failurelogeventcode','$p1failurelogeventduration',";
$sql.= "'$p1voltagesag','$p1voltagesag1','$p1voltagesag2','$p1voltagesag3','$p1voltageswell','$p1voltageswell1','$p1voltageswell2','$p1voltageswell3',";
$sql.= "'$p1gasm3devicetype','$p1gasm3serial','$p1timestampgasm3unix','$p1timestampgasm3','$p1gasm3',";
$sql.= "'$EnergyimportDelta','$EnergyimportPeakOffDelta','$EnergyimportPeakDelta',";
$sql.= "'$EnergyexportDelta','$EnergyexportPeakOffDelta','$EnergyexportPeakDelta',";
$sql.= "'$EnergyimportDaily','$EnergyexportDaily'";
$sql.= ")";

// Execute Query

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully\n";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error . "<br>\n\t";
};

// Close connection
$conn->close();

// **********************************************************
// *** Post data directly to PVoutput.org via CURL
// Old post data
//exec ('curl -s -d "d='.date('Ymd', $p1timestampunix).'" -d "t='.date('H:i', $p1timestampunix).'" -d "v4='.$p1pimport.'" -H "X-Pvoutput-Apikey: '.$pvoapi.'" -H "X-Pvoutput-SystemId: '.$pvosid.'" '.$pvourladd.'');

exec ('curl -s -d "d='.date('Ymd', $p1timestampunix).'" -d "t='.date('H:i', $p1timestampunix).'" -d "v3='.$EnergyimportDaily.'" -H "X-Pvoutput-Apikey: '.$pvoapi.'" -H "X-Pvoutput-SystemId: '.$pvosid.'" '.$pvourladd.'');

?>
