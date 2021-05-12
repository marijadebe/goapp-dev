<?php
	require_once '../globaldata.php';
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$from = $conn->real_escape_string($_GET['thisuser']);
	$to = $conn->real_escape_string($_GET['otheruser']);
	$zprava = $conn->real_escape_string(htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'));
	$odeslani = date("Y-m-d G:i:s");
	$sql = "INSERT INTO zpravy(fromuser,touser,zprava,dat_od,precteno) SELECT hrcone.id AS 'from',hrctwo.id AS 'to','$zprava' AS 'zprava', '$odeslani' AS 'dat_od', '0' AS precteno FROM hraci AS hrcone JOIN hraci AS hrctwo WHERE hrcone.prezdivka='$from' AND hrctwo.prezdivka='$to'";
	$conn->query($sql);
	$conn->close();
?>