<?php
	require_once '../globaldata.php';
	$ja = $_GET['thisuser'];
	$on = $_GET['otheruser'];
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		 	die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT *,odhrace.prezdivka  AS 'prone',prohrace.prezdivka AS 'prtwo' FROM zpravy JOIN hraci AS odhrace ON zpravy.fromuser=odhrace.id JOIN hraci AS prohrace ON zpravy.touser=prohrace.id WHERE (odhrace.prezdivka='$ja' AND prohrace.prezdivka='$on') OR (odhrace.prezdivka='$on' AND prohrace.prezdivka='$ja') ORDER BY zpravy.dat_od DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		$json = '{"radek":[';
		if($result->num_rows > 10) {
			for ($i=0; $i < 10; $i++) { 
				$row = $result->fetch_assoc();
				$from = '{"from":"'.$row['prone'].'",';
				$to = '"to":"'.$row['prtwo'].'",';
				$zprava = '"zprava":"'.$row['zprava'].'",';
				$datetime = '"datetime":"'.$row['dat_od'].'"},';
				$radek = $from.''.$to.''.$zprava.''.$datetime; 
				$json = $json.''.$radek;
			}
		}else {
			while($row = $result->fetch_assoc()) {
				$from = '{"from":"'.$row['prone'].'",';
				$to = '"to":"'.$row['prtwo'].'",';
				$zprava = '"zprava":"'.$row['zprava'].'",';
				$datetime = '"datetime":"'.$row['dat_od'].'"},';
				$radek = $from.''.$to.''.$zprava.''.$datetime; 
				$json = $json.''.$radek;
			}	
		}
		
		$json = rtrim($json,",");
		$json = $json.']}';
		echo $json;
	}
	$conn->close();
?>