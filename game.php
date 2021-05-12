<?php
ob_start();
session_start();
if(!isset($_SESSION['name'])) {
	header("Location: index.php");
	die();
}
error_reporting(E_ALL ^ E_WARNING); 
require_once './globaldata.php';
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
	<title>Game</title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<style type="text/css">
		#loading-container {
			position:fixed;
			left: 0;
			right: 0;
			display: inline-flex;
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
		#loading-svg {
			width: 25%;
			height: 25%;
			animation-name: loadinganimate;
  			animation-duration: 4s;
  			animation-iteration-count: infinite;
  			animation-direction: alternate;
		}
		.modal-body {
			text-align: center;
		}
		.isblack {
			color: white;
			background-color: #444;
			display: inline-block;
			width: 100%;
		}
		.iswhite {
			display: inline-block;
			width: 100%;
		}
		.time {
			float:right;
			font-size: 20px;
			margin: 10px;
		}
		canvas {
			background-image: url('./resource/bg.jpg');
			background-repeat: repeat;
		}
	</style>
</head>
<body>
	<script type="text/javascript">
		var gamestate;
		<?php
			$idcko = $_GET['id'];
			$diffuser;
			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
			$sql = "SELECT hry.whoisblack,hry.komi,hry.boardstate,hry.boardsize,hry.move,hry.passcounter,hry.whitetime,hry.blacktime,hrcone.prezdivka AS 'prone', hrctwo.prezdivka AS 'prtwo' FROM hry JOIN hraci AS hrcone ON hry.playerone=hrcone.id JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id WHERE hry.id=$idcko";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if(($row['prone'] == $_SESSION['name'] && $row['whoisblack'] == 0) || ($row['prtwo'] == $_SESSION['name'] && $row['whoisblack'] == 1)) {
					$youreblack = 1;
				}else {
					$youreblack = 0;
				}
				if(empty($row['whitetime'])) {
					$whitetime = "prazdna";
					$blacktime = "prazdna";
				}else {
					$whitetime = $row['whitetime'];
					$blacktime = $row['blacktime'];
				}
				echo "gamestate={boardsize:".$row['boardsize'].",passcounter:".$row['passcounter'].",boardstate:'".$row['boardstate']."',blacksmove:".$row['move'].", komi:".$row['komi'].",youreblack:".$youreblack.",whitetime:'".$whitetime."',blacktime:'".$blacktime."',x:-1,y:-1};";

				if($row['prone'] != $_SESSION['name']) {
					$diffuser = $row['prone'];
				}else {
					$diffuser = $row['prtwo'];
				}
			}
			$conn->close();
		?>
		console.log(gamestate);
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
	<div id="loading-container"><img src="./icons/grid.svg" id="loading-svg"></div>
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
	</nav>
	<div id="content">
		<div class="box">
			<?php 
			$hrac = $diffuser;
			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$sql = "SELECT id,rating,zeme,online,avatar FROM hraci WHERE prezdivka='$hrac'";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo "<div";
				if($youreblack == 0) {
					echo " class='isblack'";
				}else {
					echo " class='iswhite'";
				}
				echo ">";
				if(is_null($row['avatar'])) {
					echo "<img style='float:left;margin-right:5px' src='./icons/kamen.svg' width='50' height='50'>";
				}else {
					$imagebase = base64_encode($row['avatar']);
					echo "<img style='float:left;margin-right:5px' src='data:image/png;base64,".$imagebase."' width='50' height='50'>";
				}
				echo "<b>".$hrac."</b>";
				if($whitetime != "prazdna") {
					if($youreblack == 0) {
						echo "<div class='time' id='timecizi'>";
						echo str_pad(intdiv($blacktime,60),2,"0", STR_PAD_LEFT);
						echo " : ";
						echo str_pad($blacktime%60,2,"0", STR_PAD_LEFT);
						echo "</div>";
					}else {
						echo "<div class='time' id='timecizi'>";
						echo str_pad(intdiv($whitetime,60),2,"0", STR_PAD_LEFT);
						echo " : ";
						echo str_pad($whitetime%60,2,"0", STR_PAD_LEFT);
						echo "</div>";
					}
				}
				echo "<br/>";
				if($row['online'] == 1) {
					echo "<span>Online</span>";
				}else if ($row['online'] == 0) {
					echo "<span class='text-muted'>Offline</span>";
				}
				echo "</div>";
			} 
			$conn->close();
			?><br/><br/>
			<div class="modal fade" tabindex="-1" role="dialog" id="messagemodal">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title"></h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        	
			      </div>
			    </div>
			  </div>
			</div>
			<div style="text-align:center;">
			<canvas id="platno" style=""></canvas><br/>
			</div>
			<div class="btn-group" role="group" id="gamegrp" aria-label="Controls" style="width:100%;text-align:center;">
				<button class="btn btn-primary" onclick="pass()" id="passbtn">Pass</button>
				<button class="btn btn-danger" onclick="resign()" id="resignbtn">Resign</button>
			</div>
			<div class="btn-group" role="group" id="acceptgrp" aria-label="Controls" style="width:100%;text-align:center;display:none;">
				<button class="btn btn-success" onclick="accept()" id="acceptbtn">Accept</button>
			</div><br/><br/>
			<?php 
			$hrac = $_SESSION['name'];
			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$sql = "SELECT id,rating,zeme,online,avatar FROM hraci WHERE prezdivka='$hrac'";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo "<div";
				if($youreblack == 1) {
					echo " class='isblack'";
				}
				echo ">";
				if(is_null($row['avatar'])) {
					echo "<img style='float:left;margin-right:5px' src='./icons/kamen.svg' width='50' height='50'>";
				}else {
					$imagebase = base64_encode($row['avatar']);
					echo "<img style='float:left;margin-right:5px' src='data:image/png;base64,".$imagebase."' width='50' height='50'>";
				}
				echo "<b>".$hrac."</b>";
				if($whitetime != "prazdna") {
					if($youreblack == 1) {
						echo "<div class='time' id='timevlastni'>";
						echo str_pad(intdiv($blacktime,60),2,"0", STR_PAD_LEFT);
						echo " : ";
						echo str_pad($blacktime%60,2,"0", STR_PAD_LEFT);
						echo "</div>";
					}else {
						echo "<div class='time' id='timevlastni'>";
						echo str_pad(intdiv($whitetime,60),2,"0", STR_PAD_LEFT);
						echo " : ";
						echo str_pad($whitetime%60,2,"0", STR_PAD_LEFT);
						echo "</div>";
					}
				}
				echo "<br/>";
				if($row['online'] == 1) {
					echo "<span>Online</span>";
				}else if ($row['online'] == 0) {
					echo "<span class='text-muted'>Offline</span>";
				}
				echo "</div>";
			} 
			$conn->close();
			?>
		</div>
		<div class="box" id="chatwindow" style="display:flex;flex-direction:column;">
			<div class="chat-header">
				<b>Chat:&nbsp;</b><br/>
			</div><br/>
			<div class="chat-content" style="overflow-y: scroll;height:300px;">
			</div><br/>
			<div class="chat-form">
					<form style="display:flex;flex-direction:row">
						<div class="input-group">
							<input type="text" class="form-control" id="chat-form-text">
							<div class="input-group-append">
								<button class="btn btn-primary" type="button" onclick="sendmes()">Send</button>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
			var timer;
			var userid = <?php 
				$mena = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
		  			die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT id FROM hraci WHERE prezdivka='$mena'";
				$result = $conn->query($sql);
				if($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					echo $_GET['id'].$row['id'];
				} 
				$conn->close();
			?>;
			var canvas = document.getElementById("platno");
			var ctx = canvas.getContext("2d");
			window.addEventListener('resize', resizeCanvas, false);
			resizeCanvas();
			canvas.addEventListener("click", gameio);
			<?php
				$gid = $_GET['id'];
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
		  			die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT hry.whoisblack,hry.komi,hry.boardstate,hry.boardsize,hry.move,hry.passcounter,hrcone.prezdivka AS 'prone',hrctwo.prezdivka AS 'prtwo' FROM hry JOIN hraci AS hrcone ON hry.playerone=hrcone.id JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id WHERE hry.id='$gid'";
				$result = $conn->query($sql);
				echo $conn->error;
				if($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					if($row['passcounter'] == 2 || $row['passcounter'] == 3) {
						echo "gameend();";
					}else if($row['passcounter'] == 1) {
						$foo;
						if($row['prone'] == $ja) {$foo = $row['prtwo'];} else {$foo = $row['prone'];}
						echo "
						if((gamestate.blacksmove == 0 && gamestate.youreblack == 1) || (gamestate.blacksmove == 1 && gamestate.youreblack == 0)){
							$('#messagemodal').modal();
			  				$('#messagemodal .modal-body').html(\"<h4><a href='diff.php?name=".$foo."'>".$foo."</a> passes.</h4>\");
			  				$('#messagemodal .modal-title').text(\"Pass\");
			  				}";
					}
						
				}
				$conn->close();
			?>
			function resizeCanvas() {
				if(window.innerWidth > window.innerHeight) {
    				canvas.width = $(window).height()/2;
    				canvas.height = $(window).height()/2;
    				$('#chatwindow').css('width',$(window).height()/2);
    			}else {
    				canvas.width = $(window).width()/2;
    				canvas.height = $(window).width()/2;
    				$('#chatwindow').css('width',$(window).height()/2);
    			}
    			draw();
			}
			function draw() {
				ctx.beginPath();
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ctx.fillStyle = "black";
				ctx.arc(canvas.width/2, canvas.height/2, 5, 0, 2*Math.PI);
				ctx.fill();
				ctx.closePath();
				let krok = canvas.width/(gamestate.boardsize);
				for(var i = 0; i < gamestate.boardsize-1; i++) {
					for(var j = 0; j < gamestate.boardsize-1; j++) {
						ctx.beginPath();
						ctx.strokeStyle = "black";
						ctx.rect(i*krok+krok/2,j*krok+krok/2,krok,krok);
						ctx.stroke();
						ctx.closePath();
					}
				}
				let counteri = 0;
				let counterj = 0;
				for(var i = 0; i < gamestate.boardstate.length; i++) {
					if(gamestate.boardstate[i] == "W" || gamestate.boardstate[i] == "w") {
						ctx.beginPath();
						if(gamestate.boardstate[i] == "w") {
							ctx.fillStyle = "rgba(255, 255, 255, 0.5)";
						}else {
							ctx.fillStyle = "white";
						}
						ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/2,0, 2*Math.PI);
						ctx.fill();
						ctx.strokeStyle = "black";
					   	ctx.stroke();
						ctx.closePath();
						if(counteri == gamestate.x && counterj == gamestate.y) {
							ctx.beginPath();
							ctx.strokeStyle = "red";						
							ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/4,0, 2*Math.PI);
							ctx.stroke();
							ctx.closePath();
						}
						counterj++;
					}else if(gamestate.boardstate[i] == "B" || gamestate.boardstate[i] == "b") {
						ctx.beginPath();
						if(gamestate.boardstate[i] == "b") {
							ctx.fillStyle = "rgba(0, 0, 0, 0.5)";
						}else {
							ctx.fillStyle = "black";
						}
						ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/2,0, 2*Math.PI);
						ctx.fill();
						ctx.closePath();
						if(counteri == gamestate.x && counterj == gamestate.y) {
							ctx.beginPath();
							ctx.strokeStyle = "red";						
							ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/4,0, 2*Math.PI);
							ctx.stroke();
							ctx.closePath();
						}
						counterj++;
					}else if(gamestate.boardstate[i] >= '0' && gamestate.boardstate[i] <= '9') {
						if(i+1 < gamestate.boardstate.length && gamestate.boardstate[i+1] >= '0' && gamestate.boardstate[i+1] <= '9') {
							let necobla = parseInt(gamestate.boardstate[i]+""+gamestate.boardstate[i+1]);
							counterj+=necobla;
						}else {
							counterj+=parseInt(gamestate.boardstate[i]);
						}
					}else {
						counteri++;
						counterj=0;
					}
				}
			}

			var web_socket = new WebSocket("ws://localhost:3000");
			web_socket.onopen = function(event) {
				if(gamestate.blacktime == "prazdna" && gamestate.whitetime == "prazdna") {
					$('#loading-container').slideUp('slow');
				}else {
					$('#loading-container').append('<br/><br/>Waiting for the opponent..');
				}
				web_socket.send(JSON.stringify({"type":"firstcon","userid":userid,"gameid":"<?php echo $_GET['id']; ?>"}));
			};

			web_socket.onmessage = function(event) {
				console.log(event.data);
			  var datames = JSON.parse(event.data);
			  switch(datames.type) {
			  	case "resignmessage":
			  		clearInterval(timer);
			  		$('#messagemodal').modal();
			  		$('#messagemodal .modal-body').html("<h4><a href='diff.php?name="+datames.fromuser+"'>"+datames.fromuser+"</a> resigned.</h4>");
			  		let diff = Math.abs(datames.fromrat-datames.fromratold);
			  		$('#messagemodal .modal-body').append(datames.fromuser+"'s rating: "+datames.fromratold+" <b style='color: red;'>-"+diff+"</b><br/>");
			  		$('#messagemodal .modal-body').append(datames.touser+"'s rating: "+datames.toratold+" <b style='color: green;'>+"+diff+"<br/>");
			  		$('#messagemodal .modal-title').text("Game Over");
			  		$('#passbtn').attr("disabled",true);
			  		$('#resignbtn').attr("disabled",true);
			  		break;
			  	case "timelossmessage":
			  		$('#messagemodal').modal();
			  		$('#messagemodal .modal-body').html("<h4><a href='diff.php?name="+datames.fromuser+"'>"+datames.fromuser+"</a> loses on time.</h4>");
			  		let diffko = Math.abs(datames.fromrat-datames.fromratold);
			  		$('#messagemodal .modal-body').append(datames.fromuser+"'s rating: "+datames.fromratold+" <b style='color: red;'>-"+diffko+"</b><br/>");
			  		$('#messagemodal .modal-body').append(datames.touser+"'s rating: "+datames.toratold+" <b style='color: green;'>+"+diffko+"<br/>");
			  		$('#messagemodal .modal-title').text("Game Over");
			  		$('#passbtn').attr("disabled",true);
			  		$('#resignbtn').attr("disabled",true);
			  		break;
			  	case "gameendmessage":
			  		clearInterval(timer);
			  		$('#messagemodal').modal();
			  		$('#messagemodal .modal-body').html("<h4><a href='diff.php?name="+datames.winner+"'>"+datames.winner+"</a> wins.</h4>");
			  		let diff1 = Math.abs(datames.winnerelo-datames.winnereloold);
			  		$('#messagemodal .modal-body').append(datames.loser+"'s rating: "+datames.losereloold+" <b style='color: red;'>-"+diff1+"</b><br/>");
			  		$('#messagemodal .modal-body').append(datames.winner+"'s rating: "+datames.winnereloold+" <b style='color: green;'>+"+diff1+"<br/>");
			  		$('#messagemodal .modal-body').append("Whitescore: "+datames.whitescore+", Blackscore: "+datames.blackscore+"<br/>");
			  		$('#messagemodal .modal-title').text("Game Over");
			  		$('#acceptbtn').attr("disabled",true);
			  		canvas.removeEventListener(scoreio);
			  		break;
			  	case "chatmessage":
			  		console.log(datames.fromuser);
				  	$('.chat-content').append("<b>"+datames.fromuser+":</b>&nbsp;"+datames.content+"<br/>");
				  	let element = document.getElementsByClassName("chat-content")[0];
					let neco1 = element.scrollHeight;
					$(".chat-content").scrollTop(neco1);
			  		break;
			  	case "move":
			  		gamestate.passcounter = 0;
					gamestate.boardstate = datames.boardstate;
			  		gamestate.blacksmove = (gamestate.blacksmove==1) ? 0 : 1;
			  		console.log(gamestate.blacksmove);
			  		gamestate.x=datames.posx;gamestate.y=datames.posy;
			  		draw();
			  		break;
			  	case "pass":
			  		gamestate.passcounter++;
			  		$('#messagemodal').modal();
			  		$('#messagemodal .modal-body').html("<h4><a href='diff.php?name="+datames.fromuser+"'>"+datames.fromuser+"</a> passes.</h4>");
			  		$('#messagemodal .modal-title').text("Pass");
			  		gamestate.blacksmove = (gamestate.blacksmove==1) ? 0 : 1;
			  		if(datames.secpass == 1) {
			  			clearInterval(timer);
			  			$('#messagemodal .modal-body').append("<h3>Scoring phase begins.</h3>");
			  			$('#messagemodal .modal-body').append("<img width='20%' height='20%' src='./resource/animate.svg' />");
			  			gameend();
			  		}
			  		break;
			  	case "deadselcallback":
			  		clearInterval(timer);
			  		gamestate.boardstate = datames.boardstate;
			  		draw();
			  		break;
			  	case "acceptcallback":
			  		clearInterval(timer);
			  		$('#messagemodal').modal();
			  		$('#messagemodal .modal-body').html("<h4><a href='diff.php?name="+datames.from+"'>"+datames.from+"</a> accepts this score.</h4>");
			  		$('#messagemodal .modal-title').text("Score accepted");
			  		break;
			  	case "liftweil":
			  		if(gamestate.whitetime != "prazdna" && gamestate.blacktime != "prazdna" && gamestate.passcounter < 2) {
			  			timer = setInterval(odectiCas, 1000);
			  		}
					$('#loading-container').slideUp('slow');
			  		break;
			  	case "putbackweil":
			  		if(gamestate.whitetime != "prazdna" && gamestate.blacktime != "prazdna") {
			  			clearInterval(timer);
			  			$('#loading-container').slideDown('slow');
			  			let tac = JSON.stringify({"type":"savetime","gameid":"<?php echo $_GET['id']; ?>","blacktime":gamestate.blacktime,"whitetime":gamestate.whitetime});
	           			web_socket.send(tac);
			  		}
			  		break;
			  }
			};

			web_socket.onclose = function(event) {
			  if (event.wasClean) {
			    console.error(`[Websocket] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
			  } else {
			    console.error('[Websocket] Connection died');
			  }
			};

			web_socket.onerror = function(error) {
			 	 console.error("[Websocket] Error");
			};
			canvas.addEventListener('click', function() {

			 }, false);
			function gameend() {
			  	$('#passbtn').attr("disabled",true);
			  	$('#resignbtn').attr("disabled",true);
			  	$('#acceptgrp').css("display", "inline-flex");
			  	$('#gamegrp').css("display", "none");
			  	canvas.removeEventListener("click", gameio);
			  	canvas.addEventListener("click", scoreio);
			}
			function scoreio(event) {
				let rect = canvas.getBoundingClientRect(); 
	         	let x = event.clientX - rect.left; 
	           	let y = event.clientY - rect.top;
	           	let krok = canvas.width/(gamestate.boardsize);
	           	let barva;
	           	for(var i = 0; i < gamestate.boardsize+1; i++) {
	           		for(var j = 0; j < gamestate.boardsize+1; j++) {
	           			if(y < j*krok && y > j*krok-krok && x < i*krok && x > i*krok-krok) {
	           				x = j-1;
	           				y = i-1;
	           				break;
	           			}
	           		}
	           	}
	           	console.log("skocil jsem sem"+x+", "+y);
	           	let sel = JSON.stringify({"type":"deadsel","gameid":"<?php echo $_GET['id']; ?>","posx":x,"posy":y});
	           	web_socket.send(sel);
			}
			function accept() {
				web_socket.send(JSON.stringify({"type":"acceptscore","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>"}));
			}
			function resign() {
				web_socket.send(JSON.stringify({"type":"resign","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>"}));
			}
			function pass() {
				if((gamestate.blacksmove == 0 && gamestate.youreblack == 1) || (gamestate.blacksmove == 1 && gamestate.youreblack == 0)){
				web_socket.send(JSON.stringify({"type":"pass","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>"}));
				}
			}
			function sendmes() {
				if($('#chat-form-text').val() != "") {
					let test = JSON.stringify({"type":"textmes","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>","content":$('#chat-form-text').val()});
					$('#chat-form-text').val("");
					web_socket.send(test);
				}
			}

			function gameio(event) {
				if((gamestate.blacksmove == 0 && gamestate.youreblack == 1) || (gamestate.blacksmove == 1 && gamestate.youreblack == 0)){
					let rect = canvas.getBoundingClientRect(); 
	         		let x = event.clientX - rect.left; 
	           		let y = event.clientY - rect.top;
	           		let krok = canvas.width/(gamestate.boardsize);
	           		let barva;
	           		for(var i = 0; i < gamestate.boardsize+1; i++) {
	           			for(var j = 0; j < gamestate.boardsize+1; j++) {
	           				if(y < j*krok && y > j*krok-krok && x < i*krok && x > i*krok-krok) {
	           					x = j-1;
	           					y = i-1;
	           					break;
	           				}
	           			}
	           		}
	           		let tah = JSON.stringify({"type":"move","gameid":"<?php echo $_GET['id']; ?>","color":gamestate.youreblack.toString(),"posx":x,"posy":y});
	           		web_socket.send(tah);
           		}
			}
			function odectiCas() {
				if(gamestate.blacksmove == 0) {
					gamestate.blacktime--;
					if(gamestate.blacktime <= 0) {
						clearInterval(timer);
						if(gamestate.youreblack == 1) { 
							let zprav = JSON.stringify({"type":"timeloss","gameid":"<?php echo $_GET['id']; ?>","from":"<?php echo $_SESSION['name'];?>"});
							web_socket.send(zprav);
						}
					}
				}else {
					gamestate.whitetime--;
					if(gamestate.whitetime <= 0) {
						clearInterval(timer);
						if(gamestate.youreblack == 0) {
							let zprav = JSON.stringify({"type":"timeloss","gameid":"<?php echo $_GET['id']; ?>","from":"<?php echo $_SESSION['name'];?>"});
							web_socket.send(zprav);
						}
					}
				}
					let minuty = parseInt(gamestate.whitetime/60).toString();
					let sekundy = parseInt(gamestate.whitetime%60).toString();
					if(minuty.length == 1) {minuty = "0"+minuty;}
					if(sekundy.length == 1) {sekundy = "0"+sekundy;}
					let cas = minuty+" : "+sekundy;
					if(gamestate.youreblack==0) { $('#timevlastni').html(cas);}else { $('#timecizi').html(cas);}
					minuty = parseInt(gamestate.blacktime/60).toString();
				    sekundy = parseInt(gamestate.blacktime%60).toString();
				    if(minuty.length == 1) {minuty = "0"+minuty;}
					if(sekundy.length == 1) {sekundy = "0"+sekundy;}
					cas = minuty+" : "+sekundy;
					if(gamestate.youreblack==1) { $('#timevlastni').html(cas);}else { $('#timecizi').html(cas);}
			}
	</script>
</body>
</html>
<?php
	ob_flush();
?>