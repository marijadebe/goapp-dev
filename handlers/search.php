<?php
	require_once '../globaldata.php';
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	$promenna = $_GET['hodnota'];
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT prezdivka FROM hraci WHERE prezdivka LIKE '$promenna%'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$d = 0;
		while($free = $result->fetch_assoc()) {
			$array[$d]= array_values($free);
			$d++;
		}
		echo json_encode($array);
	}else {
		echo "nothing";
	}
?>