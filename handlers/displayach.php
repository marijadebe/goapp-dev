<?php
	require_once '../globaldata.php';
	$prezdivka = $_GET['jmeno'];
	$json = "";
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	$sql = "SELECT hraciuspechpropojovaci.id_uspech AS 'v_id' ,uspechy.nazev AS 'v_nazev' FROM hraciuspechpropojovaci JOIN hraci ON hraciuspechpropojovaci.id_hrace=hraci.id JOIN uspechy ON uspechy.id=hraciuspechpropojovaci.id_uspech WHERE hraci.prezdivka='$prezdivka'";
	$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$json.='{"info":[';
			while($row = $result->fetch_assoc()) {
				$json.='{"idcko":"'.$row['v_id'].'","nazev":"'.$row['v_nazev'].'"},';
			}
			$json = substr($json,0,-1);
			$json.="]}";
		}
	echo $json;
	$conn->close();
?>