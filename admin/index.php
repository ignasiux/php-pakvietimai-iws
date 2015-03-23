<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>Pakvietimai</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<!-- HTML5 shim -->
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
	<body style="width: 800px; margin: 10px auto;">
		<div class="well">				
		<?php
		include '../config.php';
		try {
			$db = new PDO("mysql:host=" . $mysql_config['host'] . ";dbname=" . $mysql_config['db_name'], $mysql_config['user'], $mysql_config['password']);
		} catch (PDOException $e) {
			echo 'Nepavyko prisijungti: ' . $e->getMessage();
		}
		$ip = $_SERVER['REMOTE_ADDR'];
		if (in_array($ip, $admin_ip)) {
		if (isset($_GET['atsijungti'])) {
			session_destroy();
			header('Location: ../index.php');
		}
			if (isset($_SESSION['vardas']) AND in_array($_SESSION['vardas'], $admin_nick)) {
			if (!isset($_GET['topai']) AND !isset($_GET['visi'])) {
			?>
			<a href="?topai" class="btn btn-large btn-block btn-primary">Pakvietimų topai</a>
			<a href="?visi" class="btn btn-large btn-block">Visi registruoti</a>
			<br>
			<p align="right"><a href="?atsijungti" class="btn btn-mini">Atsijungti</a>
			<?php
			} else if (isset($_GET['topai'])) {
			$query = $db->query("SELECT * FROM nariai WHERE id > 0 AND pakviete > 0 ORDER BY pakviete DESC LIMIT 10");
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
			} else if (isset($_GET['visi'])) {
				if (isset($_GET['istrinti']) AND isset($_GET['id'])) {
					$query = $db->prepare("DELETE FROM nariai WHERE id = ?");
					$query->execute(array($_GET['id']));
					echo '
					<div class="alert alert-success">
						Ištrinta
					</div>';
				}
				$query = $db->query("SELECT * FROM nariai ORDER BY id LIMIT 200");
				$result = $query->fetchAll();

				if ($result == false) {
					echo "Nieko nėra";
				} else {
					echo "
					<table class='table table-bordered table-striped'>
					<tr align=center bgcolor=#dadada><th>ID</th><th>Vardas</th><th>IP</th><th>Pakvietė</th><th>#</th></tr>";
					$i = 0;
						foreach ($result as $visi) {
							$i++;
							echo '<tr align=center><td>'.$visi['id'].'</td><td>'.$visi['vardas'].'</td><td>'.$visi['ip'].'</td><td>'.$visi['pakviete'].'</td><td><a href="?visi&id='.$visi['id'].'&istrinti" onclick="if (! confirm("Ar tikrai ištrinti įrašą? Šis veiksmas ištrins vartotoją iš sistemos!")) { return false; }" class="btn btn-mini btn-danger" title="Ištrini įrašą"><i class="icon-white icon-trash"></i></a></td></tr>';
						}
					echo "</table>
					<p align='right'><button class='btn btn-danger' onclick='history.go(-1);'><i class='icon-circle-arrow-left icon-white'></i> Grįžti atgal</button> ";
				}
			}
			} else {
			}
		} else {
		echo '
		<div class="alert alert-error">
			<center>
				<b>Ne čia pataikei!</b>
			</center>
		</div>';
		}
		?>
		</div>
		<p class="muted credit" align="right">Sprendimas: <a href="http://ignas.ws"><img src="http://ignas.ws/img/iws.png" width="43px"/></a></p>
	</body>
</html>