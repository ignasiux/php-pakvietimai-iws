<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>Pakvietimų sistema</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- HTML5 shim -->
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
	<body>
		<div class="container">
		<br>
			<div class="well">
<?php
include_once 'config.php';

$ip = $_SERVER['REMOTE_ADDR'];
if ($sistema == "on" OR in_array($ip, $admin_ip) == TRUE) {

if ($sistema == "off") {
echo '<div class="alert alert-error">
	<p>
		<center>
			<b>Sistema šiuo metu išjungta. Bet jūs esate admin, todėl turite priėjimą.</b>
		</center>
	</p>
</div>
';
}
//prisijungiam prie db
$db = new PDO("mysql:host=" . $mysql_config['host'] . ";dbname=" . $mysql_config['db_name'], $mysql_config['user'], $mysql_config['password']); 

$query = $db->query("SELECT * FROM nariai WHERE pakviete > 0 ORDER BY pakviete DESC LIMIT 10");
			$result = $query->fetchAll();
				if ($result > 0) {
					echo "
					<table class='table table-bordered table-striped'>
					<tr align=center bgcolor=#dadada><th>Nr.</th><th>Vartotojo vardas</th><th>Pakvietė</th></tr>";
					$i = 0;
						foreach ($result as $row) {
							$i++;
							$vardas = $row['vardas'];
							$pakviete = $row['pakviete'];
							echo '<tr align="center"><td>'.$i.'</td><td>'.$vardas.'</td><td>'.$pakviete.'</td></tr>';
						}
					echo "</table>
					<p align='right'><button class='btn btn-danger' onclick='history.go(-1);'><i class='icon-circle-arrow-left icon-white'></i> Grįžti atgal</button> ";
				} else {
					echo "Nieko nėra";
				}



} else {
echo '
<div class="alert alert-error">
	<p>
		<center>
			<b>Sistema šiuo metu išjungta.</b>
		</center>
	</p>
</div>
';
}
?>
			</div>
		<p class="muted credit" align="right">Sprendimas: <a href="http://ignas.ws"><img src="http://ignas.ws/img/iws.png" width="43px"/></a></p>
		</div>
	</body>
</html>