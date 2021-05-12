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
	<title>Joseki</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="./css/an.css">
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<style type="text/css">
		#an_moves {
			overflow-wrap: break-word;
			max-height: 200px;
			overflow-y: scroll;
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
		<div class="box"><h1><i class="fa fa-bar-chart" aria-hidden="true"></i>&nbsp;Analysis</h1>
			<hr class="mt-2 mb-3"/>
			<div id="an_content">
				<div class="btn-group btn-group-toggle" data-toggle="buttons" id="an_size">
					<label class="btn btn-light btn-sm">
				    	<input type="radio" name="options" onclick="change(7)" autocomplete="off"> 7x7
				  	</label>
				  	<label class="btn btn-light btn-sm active">
				    	<input type="radio" name="options" onclick="change(9)" autocomplete="off" checked> 9x9
				  	</label>
				  	<label class="btn btn-light btn-sm">
				    	<input type="radio" name="options" onclick="change(11)" autocomplete="off"> 11x11
				  	</label>
				  	<label class="btn btn-light btn-sm">
				    	<input type="radio" name="options" onclick="change(13)" autocomplete="off"> 13x13
				  	</label>
				</div>
				<div id="an_board"><canvas id="board" width="450" height="450"></canvas></div>
				<script src="./js/analysis.js"></script>
				<div id="an_tools" class="btn-group">
						<button class="btn btn-light btn-sm" onclick="browse(1)"><i class="fa fa-fast-backward" aria-hidden="true"></i></button>
						<button class="btn btn-light btn-sm" onclick="browse(2)"><i class="fa fa-step-backward" aria-hidden="true"></i></button>
						<button class="btn btn-light btn-sm" onclick="browse(3)"><i class="fa fa-step-forward" aria-hidden="true"></i></button>
						<button class="btn btn-light btn-sm" onclick="browse(4)"><i class="fa fa-fast-forward" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<div class="box" style="width: 150px;">
			<h2 style="text-align:center;">Moves</h2>
			<small><p id="an_moves"></p></small>
		</div> 
	</div>
</body>
</html>
<?php
	ob_flush();
?>