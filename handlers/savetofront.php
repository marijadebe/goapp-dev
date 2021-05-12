<?php
	require_once '../globaldata.php';
	
	$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
	if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
	}
	$name = $conn->real_escape_string($_GET['prezdivka']);
	$cas = $conn->real_escape_string($_GET['cas']);
	$color = $conn->real_escape_string($_GET['barva']);
	$size = $conn->real_escape_string($_GET['velikost']);
	$fromrat = $conn->real_escape_string($_GET['fromrat']);
	$torat = $conn->real_escape_string($_GET['torat']);
	$idcko = 0;
	$rating = 0;
	$sql = "SELECT id,rating FROM hraci WHERE prezdivka='$name'";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$idcko = $row['id'];
		$rating = $row['rating'];
		$fromrat = $rating-$fromrat;
		$torat = $rating+$torat;
		if($_GET['search'] == "1") {
			$sql = "SELECT * FROM fronta JOIN hraci ON fronta.id_hrace=hraci.id WHERE fronta.boardsize='$size' AND fronta.cas='$cas'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$promenna = 0;
		 		while($now = $result->fetch_assoc()) {
		    		if($rating >= $now['fromrat'] && $rating <= $now['torat'] && ((($now['color'])=="black" && $color=="white") ||  ($now['color']=="white" && $color=="black") || $now['color']=="any") && $promenna == 0  && ($now['rating'] >= $fromrat && $now['rating'] <= $torat) && $now['prezdivka'] != $name) {
		    				$promenna = 1;
		    				$boardstate = "";
		    				$whoisblack = 0;
		    				for ($i=0; $i < $size; $i++) { 
		    					$boardstate.=$size."/";
		    				}
		    				switch($now['color']) {
		    					case 'black':
			    					$whoisblack = 0;
			    					break;
			    				case 'white':
			    					$whoisblack = 1;
			    					break;
			    				case 'any':
			    					$whoisblack = rand(0,1);
		    				}
		    				$idneco = $now['id_hrace'];
		    				$boardstate = rtrim($boardstate,'/');
		    				$sql = "INSERT INTO hry (komi,boardsize,whitetime,blacktime,boardstate,accepted,playerone,playertwo,whoisblack) VALUES ('7.5', '$size', NULLIF('$cas','-1'),NULLIF('$cas','-1'),'$boardstate','1','$idneco','$idcko','$whoisblack')";
		    				$conn->query($sql);
		    				$sql = "UPDATE fronta SET vlajka='1' WHERE id_hrace='$idneco' AND boardsize='$size' ORDER BY id DESC";
		    				$conn->query($sql);
		    				$sql = "UPDATE hraci SET gp=gp+1 WHERE id='$idcko'";
		    				$conn->query($sql);
		    				$sql = "SELECT id AS 'vysl' FROM hry WHERE IFNULL(whitetime,'-1')='$cas' AND boardstate='$boardstate' AND playerone='$idneco' AND playertwo='$idcko' AND komi='7.5' AND accepted='1' AND IFNULL(blacktime,'-1')='$cas' AND whoisblack='$whoisblack' ORDER BY id DESC";
		    				$result = $conn->query($sql);
		    				$vvysledek = $result->fetch_assoc();
		    				echo $vvysledek['vysl'];
		    				$conn->close();
		    		}
		  		}
		  	}
		}else {
			$sql = "INSERT INTO fronta (id_hrace,color,fromrat,torat,boardsize,cas) VALUES ('$idcko','$color','$fromrat','$torat','$size','$cas')";
			$conn->query($sql);
			$conn->close();
		}
	}
?>