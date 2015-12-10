<?php
/**
 * By Tronsoftware tron@3tech.com.ar.
 */
include 'db.php';
$i=0;
while($row = $sql->fetch()) {
	$i++;
	$t = (time() - $row['lastEventTimestamp']) / 60;
	if ($t<=1){$DeviceStatusInx=1;}
	if ($t>1 && $t<=10){$DeviceStatusInx=2;}
	if ($t>10 && $t<=60){$DeviceStatusInx=3;}
	if ($t>60){$DeviceStatusInx=4;};
	
	$Devices[$i]["description"]=utf8_encode ($row['description']);
	$Devices[$i]["lat"]=$row['lastValidLatitude'];
	$Devices[$i]["lon"]=$row['lastValidLongitude'];
	$Devices[$i]["icon"]=$DeviceStatus[$DeviceStatusInx]['marker'];
    
    }
	header('Content-Type: application/json');
    echo json_encode($Devices);
?>
