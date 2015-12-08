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
 
    <title>GTS Device Admin</title>

    <!-- Bootstrap -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  </head>
  <body>
 
  <div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading">Dispositivos</div>
  <div class="panel-body">
    <p>Devices registrados en la plataforma
	</p>
	  <!-- Table -->
<div id="tabla"class="table-responsive">
  <table class="table table-hover table-striped table-bordered">
				<!-- Nombres de Columna -->
             <thead>
              <tr>
				<th>Last Conect </th>
				<th>accountID</th>
				<th>groupID</th>
                <th>deviceID</th>
				<th>displayName</th>
                <th>uniqueID</th>	
				<!-- mas columnas si es necesario -->				
              </tr>
             </thead>
      <?php while($row = $sql->fetch()) { ?>
      <tr class="<?php 
		$date1 = time();
		$date2 = $row['lastEventTimestamp'];
		$t = ($date1 - $date2) / 60;
		//echo $t;
		if ($t<1){echo "active";}
		elseif ($t<10){echo "success";}
		elseif ($t<100){echo "warning";}
		else echo "danger";
		?>">
		<td><?php echo round($t)." Min"; ?></td>
		<td><?php echo $row['accountID']; ?></td>
		<td><?php echo $row['groupID']; ?></td>
        <td><?php echo $row['deviceID']; ?></td>
		<td><?php echo $row['displayName']; ?></td>
        <td><?php echo $row['uniqueID']; ?></td>
		<!-- mas columnas si es necesario -->
       </tr>
      <?php } ?>
    </table>
	</div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script>
 function autoRefresh_div()
 {
      $("#tabla").load(location.href + " #tabla").fadeIn('fast');
  }
 
  setInterval('autoRefresh_div()', 5000); // refresh div after 5 secs
</script>
</body>
</html>
