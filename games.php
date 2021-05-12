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
	<title>Games</title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<style type="text/css">
		.card.box:hover {
			animation-name: cardflt;
  			animation-duration: 0.4s;
  			animation-fill-mode: forwards;

		}

		@keyframes cardflt {
			from {top:0px;}
			to {top: -5px;}
		}
	</style>
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
	<script type="text/javascript">
			function drawboard(boardstate,boardsize,id) {
				$('#'+id).append("<canvas id='can"+id+"' height='150' width='150'></canvas>");
				var ctx = $('#can'+id)[0].getContext("2d");
				ctx.beginPath();
				ctx.strokeRect(0,0,150,150);
				ctx.stroke();
				ctx.closePath();
				cellsize = (135/boardsize);
				for(var i = 0; i < boardsize-1; i++) {
					for(var k = 0; k < boardsize-1; k++) {
						ctx.beginPath();
						ctx.strokeRect(i*cellsize+15,k*cellsize+15,cellsize,cellsize);
						ctx.closePath();
					}
				}
				var board = [];
				for(var i = 0; i < boardsize; i++) {
					board[i] = [];
					for(var j = 0; j < boardsize; j++) {
						board[i][j] = "x";
					}
				}
				countersloupcu = 0;
				counterradku = 0;
				for(var i = 0; i < boardstate.length-1; i++) {
					if(boardstate[i] == "B") {
						board[counterradku][countersloupcu] = "B";
						countersloupcu++;
					}else if(boardstate[i] == "W") {
						console.log(counterradku+" , "+countersloupcu);
						board[counterradku][countersloupcu] = "W";
						countersloupcu++;
					}else if(boardstate[i] == "/") {
						countersloupcu = 0;
						counterradku++;
					}else if(boardstate[i] >= '0' && boardstate[i] <= '9') {
						if((i+1) < boardstate.length && boardstate[i+1] >= '0' && boardstate[i+1] <= '9') {
							cislo = parseInt(boardstate[i]+""+boardstate[i+1]);
							while(cislo > 0) {
								board[counterradku][countersloupcu] = "x";
								countersloupcu++;
								cislo--;
							}
							i++;
						}else {
							cislo = parseInt(boardstate[i]);
							while(cislo > 0) {
								board[counterradku][countersloupcu] = "x";
								countersloupcu++;
								cislo--;
							}
						}
					}
				}
				for(var i = 0; i < boardsize; i++) {
					for(var j = 0; j < boardsize; j++) {
						if(board[j][i] == "W") {
							ctx.beginPath();
							ctx.arc(i*cellsize+15, j*cellsize+15, cellsize/2, 0, 2*Math.PI);
							ctx.fillStyle = "white";
							ctx.fill();
							ctx.fillStyle = "black";
					   	    ctx.stroke();
							ctx.closePath();
						}else if(board[j][i] == "B") {
							ctx.beginPath();
							ctx.arc(i*cellsize+15, j*cellsize+15, cellsize/2, 0, 2*Math.PI);
							ctx.fillStyle = "black";
							ctx.fill();
							ctx.fillStyle = "black";
					    	ctx.stroke();
							ctx.closePath();
						}
					}
				}
			}
	</script>
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
	      <li class="nav-item active">
	        <a class="nav-link" href="" onclick="return false;">Games</a>
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
		<?php
			$ja = $_SESSION['name'];
			$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
			if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
			}
			$sql = "SELECT *,hry.id AS 'idco',hrcone.prezdivka AS 'prone', hrctwo.prezdivka AS 'prtwo' FROM hry JOIN hraci AS hrcone ON hry.playerone=hrcone.id JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id WHERE hry.accepted='1' AND (hrcone.prezdivka='$ja' OR hrctwo.prezdivka='$ja')";
			$result = $conn->query($sql);
			echo $conn->error;
			if($result->num_rows > 0) {
			     while ($row = $result->fetch_assoc()) {
			     	echo "<div class='card box' id='".$row['idco']."' style='text-align: center;'>";
			     	if($row['whoisblack'] == 1) {
			     		echo $row['prtwo']."<br/>";
			     	}else if($row['whoisblack'] == 0) {
			     		echo $row['prone']."<br/>";
			     	}
			     	echo "<script>drawboard(`".$row['boardstate']."`,`".$row['boardsize']."`,`".$row['idco']."`)</script>";
			     	echo "<a href='game.php?id=".$row['idco']."' class='stretched-link'></a>";
			     	if($row['whoisblack'] == 1) {
			     		echo $row['prone']."<br/>";
			     	}else if($row['whoisblack'] == 0) {
			     		echo $row['prtwo']."<br/>";
			     	}
			     	echo "</div>";
			     }

			}
		?>
		<div class="box">
			<h4>Challenges</h4>
			<div id="challenges">
			<?php
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT *,hry.id AS 'idco',hry.whitetime,hrcone.prezdivka AS 'prone' FROM hry JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id JOIN hraci AS hrcone ON hry.playerone=hrcone.id WHERE accepted='0' AND hrctwo.prezdivka='$ja'";
				$result = $conn->query($sql);
				echo $conn->error;
				if($result->num_rows> 0) {
					while ($row = $result->fetch_assoc()) {
						echo "<div class='shadow p-3 mb-5 bg-white rounded' id='jumbo".$row['prone']."'>";
							echo "<b>".$row['prone']."</b><br/>";
							echo "Komi: ".$row['komi']."<br/>";
							if($row['whitetime']) {
								echo "Time Control: ".$row['whitetime']." seconds<br/>";
							}else {
								echo "Time Control: Correspondence <br/>";
							}
							echo "Board: ".$row['boardsize']."&times;".$row['boardsize']."<br/>";
							echo "<button class='btn btn-success' onclick='accept(1,`".$row['prone']."`,`".$ja."`,`".$row['idco']."`)'>Accept</button>&nbsp;<button class='btn btn-danger' onclick='accept(0,`".$row['prone']."`,`".$ja."`,`".$row['idco']."`)'>Decline</button>";
						echo "</div>";
					}
				}
			?>
			</div>
		</div>
		<script type="text/javascript">
			if($.trim($("#challenges").html()) == '') {
				$("#challenges").html("<span class='text-muted text-center'>You have no new challenges.</span>");
			}
			function accept(akceptovano,hrac,ja,id) {
				if(akceptovano == false){
					$.ajax({
						url: './handlers/delchal.php',
						type: 'get',
						data: {
						"ja":ja,
						"otherplayer": hrac,
						"accepted":akceptovano
						},
						async: true,
						success: function(data) {
							
						}
					});
				}else {
					$.ajax({
						url: './handlers/delchal.php',
						type: 'get',
						data: {
						"ja":ja,
						"otherplayer": hrac,
						"accepted":akceptovano
						},
						async: true,
						success: function(data) {
							$("#content").prepend(`<div class='card box' id='`+id+`' style='text-align: center;'>`+hrac+`<canvas width='150' height='150'></canvas><a href='game.php?id=`+id+`' class='stretched-link'></a>`+ja+`</div>`);
						}
					});

				}
				smaz = "#jumbo"+hrac;
				$(smaz).remove();
				if($.trim($("#challenges").html()) == '') {
					$("#challenges").html("<span class='text-muted text-center'>You have no new challenges.</span>");
				}	
			}

		</script>
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