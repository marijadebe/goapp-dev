<?php
session_start();
if(!isset($_SESSION['name'])) {
	header("Location: index.php");
	die();
}
require_once './globaldata.php';
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$mena = $_SESSION['name'];
	$sql = "UPDATE hraci SET online=0 WHERE prezdivka='$mena'";
	$conn->query($sql);
				$conn->close();
	session_destroy();
	echo '<script language="javascript">window.location.href="index.php"</script>';
	die();
?>