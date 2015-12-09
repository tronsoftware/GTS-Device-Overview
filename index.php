<?php

 /**
 * By Tronsoftware tron@3tech.com.ar.
 */
  $hostname = 'localhost';
  $username = 'gts_user';
  $password = 'gts_pass';
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
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>GTS Overview</title>

    <!-- Bootstrap -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
<link rel="stylesheet" href="leaflet.label.css" />
  </head>
  <body>
  <div class="container-fluid">
 <div class="row">
   <div class="panel panel-primary">
   <div class="panel-heading text-center"><h3>GTS Overview</h3></div>
  </div>
  </div>

  <div class="row">
  <div class="panel panel-success col-md-8 ">
  <!-- Default panel contents -->
  <div class="panel-heading"><span class="glyphicon glyphicon-list">   Dispositivos</span></div>
  <div class="panel-body">
  <h4 class="text-center">Dispositivos registrados en la plataforma</h4>
 
	  <!-- Table -->
<div id="tabla"class="table-responsive">
  <table class="table table-condensed table-hover table-striped table-bordered">
				<!-- Nombres de Columna -->
             <thead>
              <tr>
				<th>Ultima Conexion</th>
				<th>Cuenta</th>
				<th>Grupo</th>
                <th>Dispositivo</th>
				<th>Description</th>
                <th>Identificador Unico</th>	
				<!-- mas columnas si es necesario -->				
              </tr>
             </thead>
      <?php
		$i=1;
		//$Devices = []; 
		while($row = $sql->fetch()) {	
		
		$Devices[$i]["description"]=utf8_encode ($row['description']);
		$Devices[$i]["lat"]=$row['lastValidLatitude'];
		$Devices[$i]["lon"]=$row['lastValidLongitude'];
		$i++;
	  ?>
      <tr class="<?php 
		$date1 = time();
		$date2 = $row['lastEventTimestamp'];
		$t = ($date1 - $date2) / 60;
		
		if ($t<1){echo "active";}
		elseif ($t<10){echo "success";}
		elseif ($t<100){echo "warning";}
		else echo "danger";
		?>">
		<td><?php echo round($t)." Min"; ?></td>
		<td><?php echo $row['accountID']; ?></td>
		<td><?php echo $row['groupID']; ?></td>
        <td><?php echo $row['deviceID']; ?></td>
		<td><?php echo utf8_encode ($row['description']); ?></td>
        <td><?php echo $row['uniqueID']; ?></td>
		<!-- mas columnas si es necesario -->
       </tr>
      <?php 
	  } ?>
    </table>
	</div>

</div>
</div>

<div class="panel panel-success col-md-4">
<div class="panel-heading"><span class="glyphicon glyphicon-map-marker">   Mapa</span></div>
<div class="panel-body">
<div id="map" style="width: 100%; height: 400px"></div>
</div>
</div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
<script src="leaflet.label.js"></script>
	<script>
		var DevicesLayer = new L.featureGroup();
		var map = L.map('map');

		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '3tech Seguimiento Satelital',
			id: 'osm.streets'
		}).addTo(map);
		DevicesLayer.addTo(map);
		LoadDevices();
		map.fitBounds(DevicesLayer.getBounds());
		
function LoadDevices(){
		DevicesLayer.clearLayers();
		var Devices= <?php echo json_encode($Devices, JSON_PRETTY_PRINT); ?>;
		var DeviceCount= <?php echo $i; ?>;
		for(var i=1;i<DeviceCount;i++){
			L.marker([Devices[i].lat, Devices[i].lon]).bindLabel(Devices[i].description, { noHide: true, direction: 'auto'}).addTo(DevicesLayer);
		}
}
	</script>
 <script>
 function autoRefresh_div()
 {
      $("#tabla").load(location.href + " #tabla").fadeIn('fast');
	  LoadDevices();
  }
 
  setInterval('autoRefresh_div()', 5000); // refresh div after 5 secs
 </script>
 
</body>
</html>
