<?php
	require_once '../globaldata.php';
	$friendsname = $_GET['friendsname'];
	$ja = $_GET['myname'];
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "DELETE pratele FROM pratele JOIN hraci AS hracjedna ON pratele.nameone=hracjedna.id JOIN hraci AS hracdva ON pratele.nametwo=hracdva.id WHERE (hracjedna.prezdivka='$ja' AND hracdva.prezdivka='$friendsname') OR (hracjedna.prezdivka='$friendsname' AND hracdva.prezdivka='$ja')";
	$conn->query($sql);
	$sql = "DELETE zpravy FROM zpravy JOIN hraci AS odhrace ON zpravy.fromuser=odhrace.id JOIN hraci AS prohrace ON zpravy.touser=prohrace.id WHERE (odhrace.prezdivka='$ja' AND prohrace.prezdivka='$friendsname') OR (odhrace.prezdivka='$friendsname' AND prohrace.prezdivka='$ja')";
	$conn->query($sql);
	$conn->close();
?>