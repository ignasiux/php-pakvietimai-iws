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

if (isset($_GET['atsijungti'])) {
	session_destroy();
	header('Location: login.php');
}
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

$pakviete = $_GET['pakviete'];

//prisijungiam prie db
try {
    $db = new PDO("mysql:host=" . $mysql_config['host'] . ";dbname=" . $mysql_config['db_name'], $mysql_config['user'], $mysql_config['password']);
} catch (PDOException $e) {
    echo 'Nepavyko prisijungti: ' . $e->getMessage();
}

if (!$pakviete) {
$query = $db->prepare("SELECT * FROM nariai WHERE ip = ?");
	$query->execute(array($ip));
	$row = $query->fetch();
		// Registracijos vykdymas
		if (isset($_POST['registruotis'])) {
		$vardas = $_POST['vardas'];
		$pass = $_POST['pass'];
		$pass2 = $_POST['pass2'];
		$pass_pakeista = hash('sha256', $pass);
		$pass_pakeista2 = hash('sha256', $pass2);
			$query = $db->prepare("SELECT * FROM nariai WHERE vardas = ?");
				$query->execute(array($vardas));
				$row = $query->fetch();
				// Tikrinam ar vardas neužimtas
			if ($row > 0) {
				echo '
				<div class="alert alert-error">
					<center>
						<b>Šis vardas jau registruotas</b>
					</center>
				</div>';
				// Tikrinam ar vardas/slaptažodis nėra tuščias
			} else if ($vardas == "" OR $pass == "" OR $pass2 == "") {
				echo '
				<div class="alert alert-error">
					<center>
						<b>Jūs neįvedėte vardo ir/arba slaptažodžio</b>
					</center>
				</div>';
				// Tikrinam ar varde yra _, t.y ar formatu Vardas_Pavardė
			} else if ($vardas_pavarde == "taip" AND strpos($vardas,'_') == false) {
				echo '
				<div class="alert alert-error">
					<center>
						<b>Vardas turi būti formatu Vardas_Pavardė</b>
					</center>
				</div>';
				// Tikrinam ar pass sutampa
			} else if ($pass_pakeista != $pass_pakeista2) {
				echo '
				<div class="alert alert-error">
					<center>
						<b>Slaptažodžiai nesutampa</b>
					</center>
				</div>';
				// Tikrinam ar pass yra bent 4 simbolių
			} else if (strlen($pass) < 4) {
				echo '
				<div class="alert alert-error">
					<center>
						<b>Slaptažodis per trumpas. Mažiausiai 4 simboliai</b>
					</center>
				</div>';
				// Jei viskas ok, užregistruoja
			} else {
				$query = $db->prepare("INSERT INTO nariai (vardas, ip, slaptazodis) VALUES (?, ?, ?)");
					$query->execute(array($vardas, $ip, $pass_pakeista));
				header('Location: login.php');
			}
		}
		// Jei neprisijungęs, rodom registraciją
		if (!isset($_SESSION['vardas'])) {
		?>
	<!-- Registracija -->
<div class="alert alert-info">
	<p>
		<center>
			<?php echo $info_zinute; ?>
		</center>
	</p>
</div>
<form action="" method="post">
	<center>
	<h2 class="form-signin-heading">Registracija</h2>
		<input type="text" name="vardas" class="text" style="width: 220px;" placeholder="<?php if ($vardas_pavarde == "taip") { echo "Vardas_Pavardė"; } else { echo "Vardas"; }?>"/><br />
		<input type="password" name="pass" class="text" style="width: 220px;" placeholder="Slaptažodis"/><br />
		<input type="password" name="pass2" class="text" style="width: 220px;" placeholder="Pakartokite slaptažodį"/><br />
		<button type="submit" class="btn btn-success" name="registruotis" onclick="if (! confirm('Ar teisingai suvedėte informaciją?')) { return false; }"><i class="icon-ok-sign icon-white"></i> Registruotis</button> <a class="btn btn-primary" href="login.php">Prisijungimas</a>
	</center>
</form>
<p align="right"></p>

		<?php
		// Jei jau prisijungęs
	} else if (isset($_SESSION['vardas'])) {
	$query = $db->prepare("SELECT * FROM nariai WHERE vardas = ?");
		$query->execute(array($_SESSION['vardas']));
		$row = $query->fetch();
		$vardas = $row['vardas'];
		$url = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'?pakviete='.$row['vardas'];
	?>
		<center><span class="badge badge-success" style="font-size: 15px">Jūs pakvietėte: <?=$row['pakviete']?></span></center>
		<br>
		<center>Jūsų pakvietimo nuoroda:</center><br />
		<center><input type="text" value="<?=$url?>" onclick="highlight(this)" style="width:700px"></center>
		<br>
		<p align="right"><?php if (in_array("$vardas", $admin_nick)) { echo '<a href="admin" class="btn btn-success btn-mini">Admin panelė</a> '; }?><a href="?atsijungti" class="btn btn-mini">Atsijungti</a>
	<?php
		// Jeigu neprisijungęs, nukreipia į login page
	} else {
		header('Location: login.php');
	}
	// Jei pakvieciamas zmogus
} else if ($pakviete) {

// Funkcija patikrinti iš kurios šalies

include_once 'geoiploc.php';
$visitor = getCountryFromIP($ip, " NamE ");

$query = $db->prepare("SELECT * FROM pakviesti WHERE ip = ?");
	$query->execute(array($ip));
	$rowas = $query->fetch();
		// Tikrinam ar jau pakviestas
	if ($rowas > 0) {
		echo '
		<div class="alert alert-error">
			<center>
				<b>Jūs jau pakviestas!</b><br /><br />
				<a class="btn btn-danger" href="http://'.$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF'].'".><i class="icon-circle-arrow-left icon-white"></i> Į pakvietimų sistemą</a>
			</center>
		</div>';
		// Jei nepakviestas vykdom toliau
	} else {
	// tikrinimui ar toks vartotojas yra
		$query = $db->prepare("SELECT * FROM nariai WHERE vardas = ?");
		$query->execute(array($pakviete));
		$row = $query->fetch();
		// tikrinimui ar kviecia ne save
			$query2 = $db->prepare("SELECT * FROM nariai WHERE ip = ?");
			$query2->execute(array($ip));
			$rowas = $query2->fetch();
			// Tikrinam ar sistemoje egzistuoja vartotojas
		if ($row == 0) {
			echo '
			<div class="alert alert-error">
				<center>
					<b>Vartotojas vardu ' . $pakviete . ' sistemoje neegzistuoja!</b><br /><br />
					<a class="btn btn-danger" href="http://'.$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF'].'".><i class="icon-circle-arrow-left icon-white"></i> Į pakvietimų sistemą</a>
				</center>
			</div>';
			// Tikrinam ar kviečia ne save
		} else if ($rowas['vardas'] == $pakviete) {
			echo '
			<div class="alert alert-error">
				<center>
					<b>Jūs negalite pakviesti savęs.</b><br /><br />
					<a class="btn btn-danger" href="http://'.$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF'].'".><i class="icon-circle-arrow-left icon-white"></i> Į pakvietimų sistemą</a>
				</center>
			</div>';
			// Tikrinam iš kokios šalies, jei iš netinkamos, atmetama
		} else if ($visos_salys == "ne" AND !in_array("$visitor", $salys)) {
			echo '
			<div class="alert alert-error">
				<center>
					<b>Iš šios šalies pakvietimai nepriimami.</b><br /><br />
					<a class="btn btn-danger" href="http://'.$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF'].'".><i class="icon-circle-arrow-left icon-white"></i> Į pakvietimų sistemą</a>
				</center>
			</div>';
			// Jei viskas ok, pakvietima užskaitom
		} else {
			$query = $db->prepare("UPDATE nariai SET pakviete = pakviete+? WHERE vardas = ?");
				$query->execute(array(1, $pakviete));
			$query = $db->prepare("INSERT INTO pakviesti (pakviete, ip, salis) VALUES (?, ?, ?)");
				$query->execute(array($pakviete, $ip, $visitor));
			echo '
			<div class="alert alert-success">
				<center>
					<b>Jūs sėkmingai pakviestas vartotojo ' . $pakviete . '</b><br /><br />
				</center>
			</div>';
			header('Refresh: '.$laikas.'; url='.$nukreipimas.'');
		}
	}
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