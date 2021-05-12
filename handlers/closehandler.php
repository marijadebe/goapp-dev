<?php
	require_once '../globaldata.php';
	$name = $_GET['uzivatel'];
	$iocontrol = $_GET['iocontrol'];
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if($iocontrol == 0) {
		$sql = "UPDATE hraci SET online=0 WHERE prezdivka='$name'";
		$conn->query($sql);
	}else {
		$sql = "UPDATE hraci SET online=1 WHERE prezdivka='$name'";
		$conn->query($sql);
	}
	$conn->close();

?>