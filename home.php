<?php
ob_start();
session_start();
if(!isset($_SESSION['name'])) {
	header("Location: index.php");
	die();
}
require './globaldata.php';
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
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		#matchm {
			display: flex;
			text-align: center; 
			justify-content: space-around;
			flex-flow: row wrap;
		}
		p {
			margin: 0;
			padding: 0;
		}
		div.card.box {
			min-width: 30%;
			padding: 0;
			margin: 6px;
			cursor: pointer;
		}
		div.card.box:hover {
			animation-name: cardflt;
  			animation-duration: 0.4s;
  			animation-fill-mode: forwards;
		}
		@keyframes cardflt {
			from {top:0px;}
			to {top: -5px;}
		}
		input[type=range] {
			position: relative;
			top: 5px;
		}
		#waiting-container {
			position:fixed;
			left: 0;
			right: 0;
			display: none;
			width: 100vw;
			height: 100vh;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			background-color: white;
			z-index: 11111;
		}
		@keyframes loadinganimate {
  			from {transform: rotate(0deg);}
  			to {transform: rotate(180deg);}
		}
		#waiting-svg {
			width: 25%;
			height: 25%;
			animation-name: loadinganimate;
  			animation-duration: 4s;
  			animation-iteration-count: infinite;
  			animation-direction: alternate;
		}
		#waiting-text {
			text-align: center;
		}
	</style>
	<div id="waiting-container"><img src="./icons/grid.svg" id="waiting-svg"><div id="waiting-text"></div></div>
	<div id="header">
		<h1><img src="./icons/grid.svg" height="30" width="30">&nbsp;Go</h1>
	</div>
	<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark d-flex flex-row">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style='float:right;'>
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse order-3 order-lg-2" id="navbarNav">
	    <ul class="navbar-nav">
	      <li class="nav-item active">
	        <a class="nav-link" href="" onclick="return false;">Home</a>
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
	    <form style='margin-left:6px;' class='form-inline order-2 order-lg-3' method="post" action="">
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
			<div>
				<h2>Quick pairing <button style="float:right;" class="btn btn-light" data-toggle="modal" data-target="#settingmodal"><i class="fa fa-cog" aria-hidden="true"></i></button></h2>
			</div>
			<div id="matchm">	
				<div class='card box' onclick='zarad(-1)'><p class="display-4">&infin;</p><small class="text-muted">UNLIMITED</small></div>
				<div class='card box' onclick='zarad(3599)'><p class="display-4">1</p><small class="text-muted">HOUR</small></div>
				<div class='card box' onclick='zarad(3000)'><p class="display-4">50</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(2400)'><p class="display-4">40</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(1800)'><p class="display-4">30</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(1200)'><p class="display-4">20</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(600)'><p class="display-4">10</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(300)'><p class="display-4">5</p><small class="text-muted">MINUTES</small></div>
				<div class='card box' onclick='zarad(45)'><p class="display-4">45</p><small class="text-muted">SECONDS</small></div>
			</div>
			<div class="modal fade" tabindex="-1" role="dialog" id="settingmodal">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title">Settings</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        	<form>
			        		Board size:<br/>
				        	<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="boardsize" id="inlineRadio1" value="7">
							  <label class="form-check-label" for="inlineRadio1">7x7</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" checked="checked" name="boardsize" id="inlineRadio2" value="9">
							  <label class="form-check-label" for="inlineRadio2">9x9</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="boardsize" id="inlineRadio3" value="11">
							  <label class="form-check-label" for="inlineRadio3">11x11</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="boardsize" id="inlineRadio4" value="13">
							  <label class="form-check-label" for="inlineRadio4">13x13</label>
							</div><br/>
							Your color:<br/>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" checked="checked" name="barvicka" id="inlineRadio5" value="any">
							  <label class="form-check-label" for="inlineRadio5">Any</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="barvicka" id="inlineRadio6" value="white">
							  <label class="form-check-label" for="inlineRadio6">White</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="barvicka" id="inlineRadio7" value="black">
							  <label class="form-check-label" for="inlineRadio7">Black</label>
							</div><br/>
							Player's strength:<br/>
							<label id="slidelbl1"></label>
							<input type="range" class="custom-range lead" style="width:25%" min="10" max="500" step="10" value="300" name="slider1" oninput="slided(1,this.value);">&nbsp;-/+	
							<input type="range" class="custom-range lead" style="width:25%" min="10" max="500" step="10" value="250" name="slider2" oninput="slided(2,this.value);">
							<label id="slidelbl2"></label>
			        	</form>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
		<div class="box" style="height:360px"><h2>Players</h2>
			<form>
				<input type="text" id="browse" onkeyup="search()" autocomplete="off" class='form-control'>
			</form>
			<div id="hledani"></div>
			<br/>
			<?php
			?>
			<script type="text/javascript">
				var t_evoker;
				function kontrola() {
					console.log("neconeconeoc");
					let mena = "<?php echo $_SESSION['name']; ?>";
					let size = document.querySelector('input[name="boardsize"]:checked').value;
					let color = document.querySelector('input[name="barvicka"]:checked').value;
					$.ajax({
							url: './handlers/checkvlajku.php',
							type: 'get',
							data: {
								"prezdivka":mena,
								"velikost":size,
								"barva":color
							},
							async: true,
							success: function(vvysledek){
									if(vvysledek) {
										$.ajax({
										url: './handlers/vyjdizfronty.php',
										type: 'get',
										data: {
										"prezdivka":mena
										},
										async: true,
										success: function(){
											
										}
									});
									window.onbeforeunload = null;
									window.location.href = "./game.php"+"?id="+vvysledek;
								}
							}
					});
				}
				function zrusFrontu() {
						clearInterval(t_evoker);
						$('#waiting-container').slideUp('slow');
						let mena = "<?php echo $_SESSION['name']; ?>";
						$.ajax({
								url: './handlers/vyjdizfronty.php',
								type: 'get',
								data: {
								"prezdivka":mena
								},
								async: true,
								success: function(){
									
								}
							});
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

				}
				function zarad(time) { 
					let size = document.querySelector('input[name="boardsize"]:checked').value;
					let color = document.querySelector('input[name="barvicka"]:checked').value;
					let fromrat = $('#slidelbl1').text();
					let torat = $('#slidelbl2').text();
					let mena = "<?php echo $_SESSION['name']; ?>";
					$('#waiting-container').slideDown('slow').css('display','inline-flex');
					$('#waiting-text').html("");
					$('#waiting-text').append('<br/><br/>Finding an opponent...<br/><br/>');
					$('#waiting-text').append('<button class="btn btn-danger" onclick="zrusFrontu()">Cancel</button>');
					$.ajax({
						url: './handlers/savetofront.php',
						type: 'get',
						data: {
							"cas":time,
							"prezdivka":mena,
							"barva":color,
							"velikost":size,
							"fromrat":fromrat,
							"torat":torat,
							"search":"1"
						},
						async: true,
						success: function(vvysledek){
							if(vvysledek) {
								window.location.href = "./game.php"+"?id="+vvysledek;
							}
						}
					});
					window.onbeforeunload = function() {
						zrusFrontu();
					 	return 'Are you sure you want to leave the page?' ;
					}
					$.ajax({
						url: './handlers/savetofront.php',
						type: 'get',
						data: {
							"cas":time,
							"prezdivka":mena,
							"barva":color,
							"velikost":size,
							"fromrat":fromrat,
							"torat":torat,
							"search":"0"
						},
						async: true,
						success: function(){
							t_evoker = setInterval(kontrola, 2000);
						}
					});
				}
				slided(1, 260);slided(2, 250);
				function slided(who, hodnota) {
					if(who == 1) {
						$('#slidelbl1').html(500-hodnota+10);
					}else {
						$('#slidelbl2').html(hodnota);
					}
				}

				search();
				function search() {
					var hodnota = document.getElementById("browse").value;
					$.ajax({
						url: './handlers/search.php',
						type: 'get',
						data: {
						"hodnota": hodnota
						},
						async: true,
						success: function(data){ 
								if(data != 'nothing') {
									var obj = JSON.parse(data);
									if(obj.length < 10) {
										for(var i = 0; i < obj.length; i++) {
											if(i == 0) {
												document.getElementById("hledani").innerHTML = "<a href='diff.php?name="+obj[i]+"'>"+obj[i]+"</a><br/>";
											}else {
											document.getElementById("hledani").innerHTML += "<a href='diff.php?name="+obj[i]+"'>"+obj[i]+"</a><br/>";
											}
										}
									}else {
										for(var i = 0; i < 10; i++) {
											if(i == 0) {
												document.getElementById("hledani").innerHTML = "<a href='diff.php?name="+obj[i]+"'>"+obj[i]+"</a><br/>";
											}else {
											document.getElementById("hledani").innerHTML += "<a href='diff.php?name="+obj[i]+"'>"+obj[i]+"</a><br/>";
											}
										}
										document.getElementById("hledani").innerHTML += "...";
								}
							}
						 }
					});

				}
			</script>
		</div>
		<div class="box">
			<h2>Leaderboard</h2>
			<ul class="list-group">
			<?php
					$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
					if ($conn->connect_error) {
		  				die("Connection failed: " . $conn->connect_error);
					}
					$sql = "SELECT prezdivka,rating FROM hraci ORDER BY rating DESC";
					$result = $conn->query($sql);
					echo $conn->error;
					if ($result->num_rows > 0) {
						for ($i=0; $i < 5; $i++) { 
							$poradi = $i+1;
							$row = $resultarray = $result->fetch_assoc();
							echo "<li class='list-group-item'><span class='badge badge-primary badge-pill'>".$poradi."</span>&nbsp;<a href='diff.php?name=".$row["prezdivka"]."'>".$row['prezdivka']."</a>&nbsp;<small>[".$row["rating"]."]</small></li>";
						}					
					}
					$conn->close();

			?></ul>
		</div>

		<div class="box">
			<b><img src="./icons/person.svg"> <?php echo $_SESSION['name']; ?></b><br/>
			&nbsp;&nbsp;&nbsp;rating:&nbsp;<?php
				$mena = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
		  				die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT rating FROM hraci WHERE prezdivka='$mena'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					$resultarray = $result->fetch_assoc();
					$result = reset($resultarray);
					echo $result;
					$conn->close();
				}else {
					$conn->close();
				}

			?><br/>
				&nbsp;&nbsp;&nbsp;<a href="acc.php">Profile</a>
				<?php
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
		  				die("Connection failed: " . $conn->connect_error);
				}	
				$sql = "SELECT COUNT(*) FROM pratele JOIN hraci ON pratele.nametwo=hraci.id WHERE status='0' AND hraci.id='$ja'";
				$result = $conn->query($sql);
				if($result->num_rows > 0) {
					$ass = $result->fetch_assoc();
					$result = reset($ass);
					if($result > 0) {
						echo "<span class='badge badge-info'>".$result."</span>";
					}
				}
				$conn->close();

				?><br/>
				&nbsp;&nbsp;&nbsp;<a href="mes.php">Messages</a><br/>
				&nbsp;&nbsp;&nbsp;<a href="joseki.php">Analysis</a><br/>
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