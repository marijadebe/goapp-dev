<?php
	require_once '../globaldata.php';
	
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
	}
	$prezdivka = $_GET['prezdivka'];
	$barva = $_GET['barva'];
	$velikost = $_GET['velikost'];
	$sql = "SELECT fronta.vlajka AS 'vlajka',hraci.id AS 'idcko' FROM fronta JOIN hraci ON fronta.id_hrace=hraci.id WHERE hraci.prezdivka='$prezdivka' AND fronta.boardsize='$velikost' AND fronta.color = '$barva' ORDER BY fronta.id DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		if($row['vlajka'] == 1) {
			$boardstate = "";
			$idcko = $row['idcko'];
			$sql = "UPDATE hraci SET gp=gp+1 WHERE id='$idcko'";
			$conn->query($sql);
			for ($i=0; $i < $velikost; $i++) { 
		   		$boardstate.=$velikost."/";
		    }
		    $boardstate = rtrim($boardstate,'/');
			$sql = "SELECT id FROM hry WHERE playerone='$idcko' AND boardstate='$boardstate' AND accepted='1' ORDER BY id DESC";
			$result = $conn->query($sql);
			$vvysledek = $result->fetch_assoc();
			$vvysledek = $vvysledek['id'];
			echo $vvysledek;
		}
	}
?>