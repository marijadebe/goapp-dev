<?php
require_once '../globaldata.php';
$promenna = $_GET['hodnota'];
$jmeno =  $_GET['jmeno'];
$ja = $_GET['ja'];
$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
}

if($promenna == 1) {
	$sql = "UPDATE pratele JOIN hraci AS hracjedna ON pratele.nameone=hracjedna.id JOIN hraci AS hracdva ON pratele.nametwo=hracdva.id SET pratele.status='1' WHERE hracdva.prezdivka='$ja' AND hracjedna.prezdivka='$jmeno' AND pratele.status='0'";
	$conn->query($sql);
}else if($promenna == 0) {
	$sql = "DELETE pratele FROM pratele JOIN hraci AS hracjedna ON pratele.nameone=hracjedna.id JOIN hraci AS hracdva ON pratele.nametwo=hracdva.id WHERE hracdva.prezdivka='$ja' AND hracjedna.prezdivka='$jmeno' AND pratele.status='0'";
	$conn->query($sql);
}
$conn->close();
?>