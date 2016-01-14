<?PHP
// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid
include '/usr/local/bin/dsmrp1spot/p1spot.conf';


// Create connection
$conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

// *** Query

$sql = "SELECT";
$sql.= " TimeStamp, TimeStampmysql, Energyimport, Energyexport, Gasm3, EnergyimportPeakOff, EnergyimportPeak, EnergyexportPeakOff, EnergyexportPeak"; 

$sql.= " FROM P1device";
$sql.= " WHERE HOUR(TimeStampmysql)=0 AND MINUTE(TimeStampmysql)=0";
$sql.= " ORDER BY TimeStamp DESC"; 

/* *** collums in P1device table
$sql = "INSERT INTO P1device (";
$sql.= "Serial,SW_Version,TimeStamp,TimeStampmysql,Head,Tail,Tariff,Message,Unknown,";
$sql.= "Energyimport,EnergyimportPeakOff,EnergyimportPeak,Energyexport,EnergyexportPeakOff,EnergyexportPeak,";
$sql.= "Pimport,PacI,PacI1,PacI2,PacI3,Pexport,PacE,PacE1,PacE2,PacE3,";
$sql.= "Iac,Iac1,Iac2,Iac3,";
$sql.= "Failureany,Failurelong,Failuretimes,";
$sql.= "Usag,Usag1,Usag2,Usag3,Uswell,Uswell1,Uswell2,Uswell3,";
$sql.= "Gasm3DeviceType,Gasm3Serial,Gasm3timestamp,Gasm3timestampmysql,Gasm3";
$sql.= ") VALUES (";
$sql.= "'$p1serialkwh','$p1version','$p1timestampunix','$p1timestamp','$p1head','$p1tail','$p1tariff','$p1textmessage','$p1unknown',";
$sql.= "'$p1eneryimport','$p1energyimportpeakoff','$p1energyimportpeak','$p1eneryexport','$p1energyexportpeakoff','$p1energyexportpeak',";
$sql.= "'$p1pimport','$p1powerimport','$p1powerimportl1','$p1powerimportl2','$p1powerimportl3','$p1pexport','$p1powerexport','$p1powerexportl1','$p1powerexportl2','$p1powerexportl3',";
$sql.= "'$p1current','$p1currentl1','$p1currentl2','$p1currentl3',";
$sql.= "'$p1failureany','$p1failurelong','$p1failurelogtimes',";
$sql.= "'$p1voltagesag','$p1voltagesag1','$p1voltagesag2','$p1voltagesag3','$p1voltageswell','$p1voltageswell1','$p1voltageswell2','$p1voltageswell3',";
$sql.= "'$p1gasm3devicetype','$p1gasm3serial','$p1timestampgasm3unix','$p1timestampgasm3','$p1gasm3'";
$sql.= ")";

*/

// Execute Query
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "TimeStampmysql\t\t"."Import Total\t"."Export Total\t"."Gas"."<br>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "" . $row["TimeStampmysql"]; 
	echo "\t<b>". $row["Energyimport"]." Wh</b>";
	echo "\t<b>". $row["Energyexport"]." Wh</b>\t";
	echo "\t<b>". $row["Gasm3"]." m^3</b>";
	echo "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
 
?>
