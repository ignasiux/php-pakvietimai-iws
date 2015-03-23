<?php
/* Mysql nustatymai */
$mysql_config = array(
	'host' => 'localhost',
	'user' => '',
	'password' => '',
	'db_name' => ''
);
/* Nustatymai */
// Jei "on", tai sistema įjungta, jei "off", sistema išjungta.
$sistema = "on";
// Jei norite, kad būtų registruojamasi tik formatu "Vardas_Pavarde", tai nustatymas turėtų būti "taip", 
$vardas_pavarde = "ne";
// Žinutė rodoma mėlynam fone
$info_zinute = "Pakvietimų sistema";

/* Šalys */
// Leisti iš visų šalių ar ne. Jei "taip" - leis iš visų, jei "ne" - leis tik iš žemiau nustatytų
$visos_salys = "ne";
// Šalys iš kurių leidžiami pakvietimai. BŪTINAI ANGLIŠKOS.
$salys = array("Lithuania", "Norway", "United Kingdom", "Denmark", "Germany");

/* Admin nustatymai */
/* Ip ir nick turi būti suvesti, kad būtų priėjimas prie admin panelės. */
$admin_ip = array("127.0.0.1"); // Ip kurie gali prieiti prie panelės
$admin_nick = array("ignas"); // Nickai kurie gali prieiti prie panelės (pagal registracijos nick)


/* Nukreipimo nustatymai */
// Laikas po kurio bus nukreiptas žmogus po sėkmingo pakvietimo
$laikas = 3; 
// Nuoroda į kurią bus nukreiptas žmogus po sėkmingo pakvietimo
$nukreipimas = "http://ignas.ws/";

?>