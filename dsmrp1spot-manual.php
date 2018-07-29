<?PHP
// Include config file
// *** P1 path file     * $p1path $p1latestfile
// *** DATABASE         * $dbserver $dbusername $dbpassword $dbname
// *** PvOutput.org     * $pvoapi $pvosid
//
include '/usr/local/bin/dsmrp1spot/dsmrp1spot.conf';
//
$curlapi = "X-Pvoutput-Apikey: ".$pvoapi;
$curlsid = "X-Pvoutput-SystemId: ".$pvosid;
//
$updatestart = "1532815240"; $updateend ="1532815240";

// #################################################################
//
$con=mysqli_connect($dbserver,$dbusername,$dbpassword,$dbname);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
//
$sql="SELECT ";
$sql.="TimeStamp, EnergyimportDaily";
$sql.=" FROM P1device ";
// $sql.=" WHERE TimeStamp > 1532815249";
$sql.=" WHERE TimeStamp BETWEEN ".$updatestart." AND ".$updateend;
$sql.=" ORDER BY TimeStamp ASC ";
//$sql.=" LIMIT 10 ";

if ($result=mysqli_query($con,$sql))
  {
  // Fetch one and one row
  while ($row=mysqli_fetch_row($result))
    {
    //printf ("%s (%s)\n",$row[0],$row[1]);
    //echo $row[0]."|".date('Ymd', $row[0])."|".date('H:i', $row[0])."|".$row[1]."\n";
	
	$curldate = date('Ymd', $row[0]);
	$curltime = date('H:i', $row[0]);
    $curlv3 = $row[1] ;
    
    echo "curl -s -d d=".$curldate." -d t=".$curltime." -d v3=".$curlv3;
	//
	exec ('curl -s -d "d='.$curldate.'" -d "t='.$curltime.'" -d "v3='.$curlv3.'" -H "'.$curlapi.'" -H "'.$curlsid.'" '.$pvourladd.'', $cout, $cret);
    echo " ".$cout[0]." ".$cret."\n";
    }
  // Free result set
  mysqli_free_result($result);
}

mysqli_close($con);
?>
 
