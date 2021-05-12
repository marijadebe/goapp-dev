<?php
	require_once '../globaldata.php';
	$accept = $_GET['accepted'];
	$ja = $_GET['ja'];
	$hrac = $_GET['otherplayer'];
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if($accept == 1) {
		$sql = "UPDATE hry JOIN hraci AS hracjedna ON hry.playerone=hracjedna.id JOIN hraci AS hracdva ON hry.playertwo=hracdva.id SET hry.accepted='1' WHERE hracjedna.prezdivka='$hrac' AND hracdva.prezdivka='$ja' AND hry.accepted='0'";
		$conn->query($sql);
		$sql = "UPDATE hraci SET gp=gp+1 WHERE prezdivka='$hrac' OR prezdivka='$ja'";
		$conn->query($sql);
	}else {
		$sql = "DELETE hry FROM hry JOIN hraci AS hracjedna ON hry.playerone=hracjedna.id JOIN hraci AS hracdva ON hry.playertwo=hracdva.id WHERE hracjedna.prezdivka='$hrac' AND hracdva.prezdivka='$ja' AND hry.accepted='0'";
		$conn->query($sql);
	}
	$conn->close();
?>