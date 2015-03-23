<?php session_start(); ob_start(); ?>
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
try {
    $db = new PDO("mysql:host=" . $mysql_config['host'] . ";dbname=" . $mysql_config['db_name'], $mysql_config['user'], $mysql_config['password']);
} catch (PDOException $e) {
    echo 'Nepavyko prisijungti: ' . $e->getMessage();
}

$ip = $_SERVER['REMOTE_ADDR'];
$query = $db->prepare("SELECT * FROM nariai WHERE ip = ?");
	$query->execute(array($ip));
	$row = $query->fetch();
	if ($row > 0) {
		echo '<div class="alert alert-success">
				<center>
					<b>Prašome prisijungti</b>
				</center>
			</div>';
	}
	if (isset($_POST['vardas']) AND isset($_POST['pass'])) {
	$query = $db->prepare("SELECT * FROM nariai WHERE vardas = ?");
		$query->execute(array($_POST['vardas']));
		$row = $query->fetch();
	if ($row > 0) {
	$pass = $_POST['pass'];
	$pass_pakeista = hash('sha256', $pass);
		if ($row['slaptazodis'] == $pass_pakeista) {
			$_SESSION['vardas'] = $row['vardas'];
			header('Location: index.php');
		} else {
			echo '
			<div class="alert alert-error">
				<center>
					<b>Slaptažodis netinkamas</b>
				</center>
			</div>';
		}
		} else {
			echo '
			<div class="alert alert-error">
				<center>
					<b>Vartotojas nerastas</b>
				</center>
			</div>';
		}
	}
?>
			<div class="alert alert-info">
				<p>
					<center>
						<?php echo $info_zinute; ?>
					</center>
				</p>
			</div>
			<form method="post" action="login.php">
				<center>
					<h2 class="form-signin-heading">Prisijungimas</h2>
					<input name="vardas" type="text" class="input-block-level" style="width: 220px;" placeholder="<?php if ($vardas_pavarde == "taip") { echo "Vardas_Pavardė"; } else { echo "Vardas"; }?>"><br />
					<input name="pass" type="password" class="input-block-level" style="width: 220px;" placeholder="Slaptažodis"><br />
					<button class="btn btn-success" type="submit"><i class="icon-ok-sign icon-white"></i> Prisijungti</button> <a class="btn btn-primary" href="index.php">Registracija</a>
				<center>
			</form>
<?php
} else {
?>
<div class="alert alert-error">
	<p>
		<center>
			<b>Sistema šiuo metu išjungta.</b>
		</center>
	</p>
</div>
<?php
}
?>
			</div>
		<p class="muted credit" align="right">Sprendimas: <a href="http://ignas.ws"><img src="http://ignas.ws/img/iws.png" width="43px"/></a></p>
		</div>
	</body>
</html>