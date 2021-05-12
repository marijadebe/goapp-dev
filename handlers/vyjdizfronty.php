<?php
	require_once '../globaldata.php';
	$prezdivka = $_GET['prezdivka'];
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
	}
	$sql = "DELETE fronta FROM fronta JOIN hraci ON fronta.id_hrace=hraci.id WHERE hraci.prezdivka = '$prezdivka'";
	$conn->query($sql);
	echo $conn->error;
	$conn->close();

?>