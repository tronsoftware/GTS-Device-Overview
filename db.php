<?php
 /**
 * By Tronsoftware tron@3tech.com.ar.
 */
 $DeviceStatus[1]['class'] = 'success';
$DeviceStatus[1]['icon'] = 'glyphicon-screenshot';
$DeviceStatus[1]['marker'] = 'greenIcon';

$DeviceStatus[2]['class'] = 'info';
$DeviceStatus[2]['icon'] = 'glyphicon-ok-circle';
$DeviceStatus[2]['marker'] = 'blueIcon';

$DeviceStatus[3]['class'] = 'warning';
$DeviceStatus[3]['icon'] = 'glyphicon-remove-circle';
$DeviceStatus[3]['marker'] = 'yelowIcon';

$DeviceStatus[4]['class'] = 'danger';
$DeviceStatus[4]['icon'] = 'glyphicon-ban-circle';
$DeviceStatus[4]['marker'] = 'redIcon';


$hostname = 'localhost';
$username = 'user';
$password = 'pass';
$dbname = 'gts';

  try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    $sql = $dbh->prepare("SELECT * FROM Device");

    if($sql->execute()) {
       $sql->setFetchMode(PDO::FETCH_ASSOC);
    }
  }
  catch(Exception $error) {
      echo '<p>', $error->getMessage(), '</p>';
  }
 
 ?>
