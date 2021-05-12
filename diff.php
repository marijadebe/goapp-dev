<?php
ob_start();
session_start();
require_once './globaldata.php';
if(!isset($_SESSION['name']) || !isset($_GET['name'])) {
	header("Location: index.php");
	die();
}
if($_SESSION['name'] == $_GET['name']) {
	header("Location: acc.php");
	die();
}
$hrac = $_GET['name'];
$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT prezdivka FROM hraci WHERE prezdivka='$hrac'";
$result = $conn->query($sql);
if($result->num_rows == 0) {
	$conn->close();
	header("Location: index.php");
	die();
}
$conn->close();
error_reporting(E_ALL ^ E_WARNING); 
?>
<!DOCTYPE html>
<html>
<head>
	<!--favicon-->
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="theme-color" content="#ffffff">
	<!--endfavicon-->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $_GET['name']; ?></title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<script type="text/javascript">
		var uzivatel = "<?php echo $_SESSION['name'];?>";
		window.onload = function(){
   			$.ajax({
				url: './handlers/closehandler.php',
				type: 'get',
				data: {
				"uzivatel":uzivatel,
				"iocontrol":1
				},
				async: true,
				success: function(){
					
				}
			});
   			return null;
		}
	</script>
	<style type="text/css">
		#obsahach {
			display: flex;
		}
		.achzob {
			margin:0;
			padding: 3px;
		}
	</style>
	<div id="header">
		<h1><img src="./icons/grid.svg" height="30" width="30">&nbsp;Go</h1>
	</div>
	<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark d-flex flex-row">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style='float:right;'>
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse order-3 order-lg-2" id="navbarNav">
	    <ul class="navbar-nav">
	      <li class="nav-item">
	        <a class="nav-link" href="home.php">Home</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="games.php">Games</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="acc.php">Account</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="mes.php">Messages</a>
	      </li>
	    </ul>
	  </div>
 	  <form class='form-inline order-2 order-lg-3' method="post" action="">
	    	<span class="navbar-text"><?php echo $_SESSION['name']; ?>&nbsp;</span>&nbsp;
	    	<input type='submit' value='Logout' class="btn btn-danger btn-sm" name="neco">
	    	<?php
				if(isset($_POST['neco'])) {
					$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
					if ($conn->connect_error) {
		  				die("Connection failed: " . $conn->connect_error);
					}
					$mena = $_SESSION['name'];
					$sql = "UPDATE hraci SET online=0 WHERE prezdivka='$mena'";
					$conn->query($sql);
					$conn->close();
					$_SESSION = array();
					if (ini_get("session.use_cookies")) {
					    $params = session_get_cookie_params();
					    setcookie(session_name(), '', time() - 42000,
					        $params["path"], $params["domain"],
					        $params["secure"], $params["httponly"]
					    );
					}
					session_destroy();
					header("Location: index.php");
					die();
				}
			?>
	    </form>
	</nav><br/>
	<div id="content">
		<div class="box">
			<?php 
			$hrac = $_GET['name'];
			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$sql = "SELECT id,rating,zeme,online,avatar,gp FROM hraci WHERE prezdivka='$hrac'";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if(is_null($row['avatar'])) {
					echo "<img style='float:left;margin-right:5px' src='./icons/kamen.svg' width='50' height='50'>";
				}else {
					$imagebase = base64_encode($row['avatar']);
					echo "<img style='float:left;margin-right:5px' src='data:image/png;base64,".$imagebase."' width='50' height='50'>";
				}
				echo "<b>".$hrac."</b><br/>";
				if($row['online'] == 1) {
					echo "<span>Online</span>";
				}else if ($row['online'] == 0) {
					echo "<span class='text-muted'>Offline</span>";
				}
				echo "<br/><br/>Country: ".$row['zeme'];
				echo "<br/>Rating: ".$row['rating'];
				echo "<br/>Player ID: ".$row['id'];
				echo "<br/>Games Played: ".$row['gp'];
			} 
			$conn->close();
			?><br/><br/>
			<!-- modal -->
			<div class="modal fade" id="challenge" tabindex="-1" role="dialog" aria-labelledby="challengetitle" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="challengetitle">Challenge <?php echo $_GET['name']; ?></h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        	<form action="" method="post">
			        		<div id="form-group">
			        			Color:<br/>
			        			<select class="form-control" name="color">
			        				<option>Black</option>
			        				<option>White</option>
			        			</select>
			        		</div><br/>
			        		<div id="form-group">
			        			Size:<br/>
			        			<select class="form-control" name="size">
			        				<option>7x7</option>
			        				<option>9x9</option>
			        				<option>11x11</option>
			        				<option>13x13</option>
			        			</select>
			        		</div><br/>
			        		<div id="form-group">
			        			Komi:<br/>
			        			<input type="text" class="form-control" placeholder="7.5" name="komi">
			        		</div><br/>
			        		<div id="form-group">
			        			Time Control:<br/>
			        			<select class="form-control" name="time" onchange="shtime(this);">
			        				<option value="time">Real Time</option>
			        				<option value="notime" selected="selected">Correspondence</option>
			        			</select>
			        		</div><br/>
			        		<div id="form-group" class="slid jumbotron" style="display:none;text-align:center">
			        			<h4 id="timer" class="display-4">10&nbsp;:&nbsp;00</h4><br/>
			        			<input type="range" class="custom-range lead" min="30" max="3000" step="30" value="600" name="slider" oninput="slided(this.value);">
			        		</div>
			        		<div id="form-group">
			        			<input type="submit" value="Send" class="btn btn-primary"><br/>
			        		</div>
			        	</form>
			 			<script type="text/javascript">
			 				function shtime(hodnota) {
			 					switch(hodnota.value) {
			 						case 'time':
			 							$('.slid').show();
			 							break;
			 						case 'notime':
			 							$('.slid').hide();
			 							break;
			 					}
			 				}
			 				function slided(hodnota) {
			 					let sekundy = hodnota%60;
			 					if(sekundy == 0) {
			 						sekundy=0+(sekundy).toString();
			 					}
			 					$('#timer').html(parseInt(hodnota/60)+" : "+sekundy);
			 				}
			 			</script>
			        	<?php
			        		if(isset($_POST['color']) && isset($_POST['komi']) && isset($_POST['size'])) {
			        			$barva = $_POST['color'];
			        			$komi = $_POST['komi'];
			        			if(empty($komi)) {
			        				$komi = 7.5;
			        			}
			        			$size = $_POST['size'];
			        			$timer = $_POST['time'];
			        			$slider = $_POST['slider'];
			        			$playertwo = $_GET['name'];
			        			$playerone = $_SESSION['name'];
			        			$boardsize = substr($size,0,strpos($size,'x'));
			        			$boardstate = "";
			        			for ($i=0; $i < $boardsize; $i++) { 
			        				$boardstate=$boardstate.$boardsize."/";
			        			}
			        			switch ($timer) {
			        				case 'time':
			        					$timer = $slider;
			        					break;
			        				default:
			        					$timer = 0;
			        					break;
			        			}
			        			$boardstate = rtrim($boardstate,"/");
			        			if($barva == "Black") {
			        				$whoisblack = 0;
			        			}else if($barva == "White") {
			        				$whoisblack = 1;
			        			}
			        			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
								if ($conn->connect_error) {
									die("Connection failed: " . $conn->connect_error);
								}
								$result = $conn->query($sql);
								$row = $result->fetch_assoc();
								$sql = "INSERT INTO hry(playerone,playertwo,whoisblack,komi,boardsize,accepted,boardstate,whitetime,blacktime) SELECT hrcone.id,hrctwo.id,'$whoisblack' AS 'whoisblack','$komi' AS 'komi','$boardsize' AS 'boardsize','0' AS 'accepted','$boardstate' AS 'boardstate', IF($timer=0,NULL,'$timer') AS 'whitetime', IF($timer=0,NULL,'$timer') AS 'blacktime' FROM hraci AS hrcone JOIN hraci AS hrctwo WHERE hrcone.prezdivka='$playerone' AND hrctwo.prezdivka='$playertwo'";
								$conn->query($sql);
								$conn->close();
			        		}
			        	?>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- end modal -->
			<button class="btn btn-primary" data-toggle="modal" data-target="#challenge" onclick="">Challenge</button>
			<form method="post" action="" style="display: inline-block">
				<input type="submit" class="btn btn-primary" value="Friend Request" name="addfriend"/>
				<?php
					if(isset($_POST['addfriend'])) {
						$hrac = $_GET['name'];
						$hrac2 = $_SESSION['name'];
						$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}
						$sql = "SELECT pratele.id FROM pratele JOIN hraci AS none ON pratele.nameone=none.id JOIN hraci AS ntwo ON pratele.nametwo=ntwo.id WHERE (none.prezdivka='$hrac2' AND ntwo.prezdivka='$hrac') OR (none.prezdivka='$hrac' AND ntwo.prezdivka='$hrac2')";
						$result = $conn->query($sql);
						if($result->num_rows == 0) {
							$sql = "INSERT INTO pratele(nameone,nametwo,status) SELECT none.id,ntwo.id,'0' AS 'status' FROM hraci AS none JOIN hraci AS ntwo WHERE none.prezdivka='$hrac2' AND ntwo.prezdivka='$hrac'";
							$conn->query($sql);
						}			
						$conn->close();	
					}
				?>
			</form>
		</div>
		<div class="box">
			<h2>Friends</h2>
			<?php
				$ja = $_GET['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT nameone,nametwo,none.prezdivka AS 'prone',ntwo.prezdivka AS 'prtwo' FROM pratele JOIN hraci AS none ON pratele.nameone=none.id JOIN hraci AS ntwo ON pratele.nametwo=ntwo.id WHERE (ntwo.prezdivka='$ja' OR none.prezdivka='$ja') AND status='1'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						if($row['prone'] == $ja) {
							echo "<a href='diff.php?name=".$row['prtwo']."'>".$row['prtwo']."</a><br/>";
						}else {
							echo "<a href='diff.php?name=".$row['prone']."'>".$row['prone']."</a><br/>";
						}
					}
				}
				$conn->close();
			?>
		</div>
		<div class="box">
	    	<h2>Achievements</h2>
	    	<div class="obsahach">
	    		<script type="text/javascript">
	    			var on = "<?php echo $_GET['name']; ?>";
	    			$.ajax({
						url: './handlers/displayach.php',
						type: 'get',
						data: {
						"jmeno":on,
						},
						async: true,
						success: function(data) {
							if(!data) {
								$('.obsahach').append("<span class='text-muted text-center'>Player doesn't have any achievements yet.</span>");
							}else {

								data = JSON.parse(data);
								for (var i = 0; i < data.info.length; i++) {
									$('.obsahach').append('<img class="achzob" width="50" height="50" src="./resource/achievements/'+data.info[i].idcko+'.svg" data-toggle="tooltip" data-placement="top" title="'+data.info[i].nazev+'">');
									$('[data-toggle="tooltip"]').tooltip();
								}
							}
						}
					});
	    		</script>
	    	</div>
	    </div>
	</div>
	<script type="text/javascript">
		var uzivatel = "<?php echo $_SESSION['name'];?>";
		window.onbeforeunload = function(){
   			$.ajax({
				url: './handlers/closehandler.php',
				type: 'get',
				data: {
				"uzivatel":uzivatel,
				"iocontrol":0
				},
				async: true,
				success: function(){
					
				}
			});
   			return null;
		}
	</script>
</body>
</html>
<?php
	ob_flush();
?>