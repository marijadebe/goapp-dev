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
	<title>Account</title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
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
		#obsahach {
			display: flex;
		}
		.achzob {
			margin:0;
			padding: 3px;
		}
	</style>
	<?php
		$msgfile = "";
	?>
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
	      <li class="nav-item active">
	        <a class="nav-link" href="" onclick="return false;">Account</a>
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
			<?php
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
		  			die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT * FROM hraci WHERE prezdivka='$ja'";
				$result = $conn->query($sql);
				if($result->num_rows > 0) {
					$vysledek = $result->fetch_assoc();
					if(is_null($vysledek['avatar'])) {
						echo "<h4 style='margin-bottom:0px;padding:0;'><img style='float:left;margin-right:5px' src='./icons/kamen.svg' width='100' height='100'>".$vysledek['prezdivka']."</h4>";
					}else {
						$imagebase = base64_encode($vysledek['avatar']);
						echo "<h4 style='margin-bottom:0px;padding:0;'><img style='float:left;margin-right:5px' src='data:image/png;base64,".$imagebase."' width='100' height='100'>".$vysledek['prezdivka']."</h4>";
					}
					echo "#".$vysledek['id']."<br/>";
					echo $vysledek['zeme']."<br/>";
					echo "Rating: ".$vysledek['rating']."<br/>";
				}
				$conn->close();

			?><br/>
			<form method="post" action="" enctype="multipart/form-data">
				<input type="file" class='form-control-file' name="soubor" accept=".jpg,.png,.bmp" data-toggle="tooltip" data-placement="top" title="Has to be 100x100px." style="border:0;"><br/>
				<div class="card" style="width: 18rem;">
					<div class="card-header">
  						Change password:
  						<button type="button" class="btn btn-light btn-sm collapsed" data-toggle="collapse" data-target="#changepass" style="float:right;"><i class="fa fa-plus" aria-hidden="true"></i></button>
 					</div>
 					<div class="collapse" id="changepass">
  					<div class="card-body">
				<small>Old password:</small>
				<div class="input-group mb-3">
					<input type="password" name="oldpass" class='form-control form-control-sm'><br/>
					<span class="input-group-append">
		    			<span class="input-group-text" type="button"><i onclick="oko(0)" class="fa fa-eye-slash" type="button"></i></span>
		  			</span>
		  		</div>
				<small>New password:</small>
				<div class="input-group mb-3">
					<input type="password" name="newpass" class='form-control form-control-sm'><br/>
					<span class="input-group-append">
		    			<span class="input-group-text" type="button"><i onclick="oko(1)" class="fa fa-eye-slash" type="button"></i></span>
		  			</span>
		  		</div>
					</div></div>
				</div><br/>
				<input type="submit" class='btn btn-primary' value="Save Changes">
				<?php
					if(isset($_FILES["soubor"]) && file_exists($_FILES["soubor"]["tmp_name"])){
						$imgsize = @getimagesize($_FILES["soubor"]["tmp_name"]);
						$imgwidth = $imgsize[0];
						$imgheight = $imgsize[1];
						$img = $_FILES["soubor"]["tmp_name"];
						if(!file_exists($_FILES["soubor"]["tmp_name"])) {
							$msgfile = "<div id='erroralert' class='alert alert-danger' role='alert'>No file entered.</div><script>$('#erroralert').fadeOut(4000);</script>";
						}else if($_FILES["soubor"]["size"] > 1000000) {
							$msgfile = "<div id='erroralert' class='alert alert-danger' role='alert'>Maximum image size of 1mb exceeded.</div><script>$('#erroralert').fadeOut(4000);</script>";
						}else if($imgwidth != "100" || $imgheight != "100") {
							$msgfile = "<div id='erroralert' class='alert alert-danger' role='alert'>Image has to be 100x100 pixels.</div><script>$('#erroralert').fadeOut(4000);</script>";
						}
						else {
							$data = file_get_contents($img);
							$safedata = addslashes($data);
							$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
							$ja = $_SESSION['name'];
							if ($conn->connect_error) {
			  					die("Connection failed: " . $conn->connect_error);
							}
							$sql = "UPDATE hraci SET avatar='$safedata' WHERE prezdivka='$ja'";
							$conn->query($sql);	
							header("Location: acc.php");
							die();
						}
					}
					if(!empty($_POST['oldpass']) && !empty($_POST['newpass'])) {
						$old = md5($_POST['oldpass']);
						$new = md5($_POST['newpass']);
						$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
						$ja = $_SESSION['name'];
						if ($conn->connect_error) {
			  				die("Connection failed: " . $conn->connect_error);
						}
						$sql = "SELECT heslo FROM hraci WHERE prezdivka='$ja'";
						$result = $conn->query($sql);
						if($result->num_rows > 0) {
							$row = $result->fetch_assoc();
							if($row['heslo'] == $old) {
								$sql = "UPDATE hraci SET heslo='$new' WHERE prezdivka='$ja'";
								$conn->query($sql);
							}else {
								$msgfile = "<div id='erroralert' class='alert alert-danger' role='alert'>Old passwords do not match.</div><script>$('#erroralert').fadeOut(4000);</script>";
							}
						}
					}
				?>
			</form>
		</div>
		<div class="box">
			<h2>Friends</h2>
			<div><ul id="friendlist" class='list-group'>
			<?php
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT hld.prezdivka AS 'pr1',dld.prezdivka AS 'pr2' FROM pratele JOIN hraci AS hld ON pratele.nameone=hld.id JOIN hraci AS dld ON pratele.nametwo=dld.id WHERE (dld.prezdivka='$ja' OR hld.prezdivka='$ja') AND status='1'";
				$result = $conn->query($sql);
				echo $conn->error;
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						if($row['pr1'] != $ja) {
							echo "<li class='list-group-item'><a href='diff.php?name=".$row['pr1']."'>".$row['pr1']."</a>&nbsp;&nbsp<button onclick='removefriend(`".$row['pr1']."`,`".$ja."`)' style='float:right;' class='btn btn-danger btn-sm'>&times;</button></li>";
						}else {
							echo "<li class='list-group-item'><a href='diff.php?name=".$row['pr2']."'>".$row['pr2']."</a>&nbsp;&nbsp;<button onclick='removefriend(`".$row['pr2']."`,`".$ja."`)' style='float:right;' class='btn btn-danger btn-sm'>&times;</button></li>";
						}
					}
				}
				$conn->close();
			?></ul>
			</div>
		</div>
		<script type="text/javascript">
			function removefriend(friendsname,myname) {
				$.ajax({
							url: './handlers/removehandler.php',
							type: 'get',
							data: {
							"friendsname":friendsname,
							"myname":myname
							},
							async: true,
							success: function(){
								var children = document.getElementById('friendlist').children;
								console.log("<a href='diff.php?name="+friendsname+"'>"+friendsname+"</a>");
								for (var i = 0; i < children.length; i++) {
								    if (children[i] && children[i].innerHTML.includes('<a href="diff.php?name='+friendsname+'">'+friendsname+'</a>')) {
								        document.getElementById('friendlist').removeChild(children[i]);
								        i--;
								    }
								}
								if($.trim($("#friendlist").html()) == '') {
									$("#friendlist").html("<span class='text-muted text-center'>You have no friends (yet).</span>");
								}
							}
						});
			}
		</script>
		<div class="box" id="box2">
			<h2>Friend Requests</h2>
			<div id="requesty">
			<?php
				$ja = $_SESSION['name'];
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT hld.prezdivka AS 'prz' FROM pratele JOIN hraci AS hld ON pratele.nameone=hld.id JOIN hraci AS dld ON pratele.nametwo=dld.id  WHERE dld.prezdivka='$ja' AND status='0'";
				$result = $conn->query($sql);
				if($result->num_rows > 0) {
					echo "<ul class='list-group'>";
					while($row = $result->fetch_assoc()) {
						echo "<li class='list-group-item' id='list".$row['prz']."'><a href='diff.php?name=".$row['prz']."'>".$row['prz']."</a>&nbsp;&nbsp;<button onclick='accept(`".$ja."`,`".$row['prz']."`,1)' class='btn btn-success'>Accept</button>&nbsp;<button onclick='accept(`".$ja."`,`".$row['prz']."`,0)' class='btn btn-danger'>Reject</button></li>";
					}
					echo "</ul>";
				}			
				$conn->close();	
			?>
			</div>
			<script type="text/javascript">
				if($.trim($("#requesty").html()) == '') {
					$("#requesty").html("<span class='text-muted text-center'>You have no new friend requests.</span>");
				}
				if($.trim($("#friendlist").html()) == '') {
					$("#friendlist").html("<span class='text-muted text-center'>You have no friends (yet).</span>");
				}
			</script>
			<script type="text/javascript">
				function accept(ja,jmeno,hodnota) {
					var mezikrok = "list"+jmeno;
					document.getElementById(mezikrok).remove();
					if($.trim($("#requesty ul").html()) == '') {
						$("#requesty").html("<span class='text-muted text-center'>You have no new friend requests.</span>");
					}
					if(hodnota == 1) {
						if($("#friendlist").text() == "You have no friends (yet).") {
							$("#friendlist").html("");
						}
						$("#friendlist").append(`<li class='list-group-item'><a href="diff.php?name=`+jmeno+`">`+jmeno+`</a>&nbsp;&nbsp;<button onclick='removefriend("`+jmeno+`","`+ja+`")' style='float:right;' class='btn btn-danger btn-sm'>&times;</button></li>`);
					}
					$.ajax({
						url: './handlers/friendreq.php',
						type: 'get',
						data: {
						"ja":ja,
						"jmeno": jmeno,
						"hodnota": hodnota
						},
						async: true,
						success: function(data) {
							
						}
					});
				}
			</script>
	    </div>
	    <div class="box">
	    	<h2>Achievements</h2>
	    	<div class="obsahach">
	    		<script type="text/javascript">
	    			var ja = "<?php echo $_SESSION['name']; ?>";
	    			$.ajax({
						url: './handlers/displayach.php',
						type: 'get',
						data: {
						"jmeno":ja,
						},
						async: true,
						success: function(data) {
							if(!data) {
								$('.obsahach').append("<span class='text-muted text-center'>You don't have any achievements yet.</span>");
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
	<div id="errors"><br/>
		<?php
			echo "<br/>".$msgfile;
		?>
	</div>
	<script type="text/javascript">
		function oko(number) {
			if($('input:eq('+(number+2)+')').attr("type") == "password") {
				$('input:eq('+(number+2)+')').attr({"type":"text"});
				$('i:eq('+(number+1)+')').removeClass("fa-eye-slash");
				$('i:eq('+(number+1)+')').addClass("fa-eye");
			}else {
				$('input:eq('+(number+2)+')').attr({"type":"password"});
				$('i:eq('+(number+1)+')').removeClass("fa-eye");
				$('i:eq('+(number+1)+')').addClass("fa-eye-slash");
			}
		}
		$(function () {
  			$('[data-toggle="tooltip"]').tooltip();
		})
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