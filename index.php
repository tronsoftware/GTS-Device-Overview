<?php

 /**
 * By Tronsoftware tron@3tech.com.ar.
 */
include 'db.php';

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
				<div id="tabla" class="panel-body">
					<h4 class="text-center">Dispositivos registrados en la plataforma</h4>

					<!-- Table -->
					<div  class="table-responsive">
						<table class="table table-condensed table-hover table-bordered">
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
							$i=0;

							while($row = $sql->fetch()) {
								$i++;
								$t = (time() - $row['lastEventTimestamp']) / 60;
								if ($t<=1){$DeviceStatusInx=1;}
								if ($t>1 && $t<=10){$DeviceStatusInx=2;}
								if ($t>10 && $t<=60){$DeviceStatusInx=3;}
								if ($t>60){$DeviceStatusInx=4;};

								?><tr class=<?php echo "'".$DeviceStatus[$DeviceStatusInx]['class']." clickable-row' data-inx='".$i."'";?>>
									<td><?php echo "<span class='glyphicon ".$DeviceStatus[$DeviceStatusInx]['icon']."'></span>  ".round($t)." Min"; ?></td>
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
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
<script src="leaflet.label.js"></script>
<script>
function autoRefresh_div()
{
	$("#tabla").load(location.href + " #tabla").fadeIn('fast');
	LoadDevices();
}
setInterval('autoRefresh_div()', 5000); // refresh div after 5 secs

var DevicesLayer = new L.featureGroup();
var map = L.map('map');
var LeafIcon = L.Icon.extend({
	options: {
		shadowUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/msmarker.shadow.png',
		iconSize:     [32, 32],
		shadowSize:   [59, 32],
		iconAnchor:   [16, 32],
		shadowAnchor: [16, 32],
		popupAnchor:  [-3, -76]
	}
});
var greenIcon = new LeafIcon({iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/green-dot.png'}),
blueIcon = new LeafIcon({iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/ltblue-dot.png'}),
yelowIcon = new LeafIcon({iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/yellow-dot.png'}),
redIcon = new LeafIcon({iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png'});

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

	var Devices=GetData();

	var DeviceCount=Object.keys(Devices).length;
	
	for(var i=1;i<DeviceCount;i++){
		L.marker([Devices[i].lat, Devices[i].lon], {icon: eval(Devices[i].icon)}).bindLabel(Devices[i].description, { noHide: true, direction: 'auto'}).addTo(DevicesLayer);
	}
}
function CenterDevices(i){
	var Devices=GetData();	
	map.setView(new L.LatLng(Devices[i].lat, Devices[i].lon), 15);
}
function GetData(){	
        $.ajax({
               url: 'getdata.php',
               type: 'GET',
               encoding:"UTF-8",
               dataType: 'json',
               contentType: "text/json; charset=UTF-8",
			   async: false,
               success: function(data) {
				 json = data;
				}
			});
return json; 
}
$(document).ready(function(){
    $('body').on('click','.clickable-row',function(){
        CenterDevices($(this).data("inx"));
    });
});
</script>
</html>
