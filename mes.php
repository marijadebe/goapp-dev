<?php
ob_start();
session_start();
if(!isset($_SESSION['name'])) {
	header("Location: index.php");
	die();
}
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
	<title>Messages</title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<style type="text/css">
		.datum {
			font-size: 10px;
			clear:both;
			padding:0;
		}
		.zpravy {
			margin-bottom: 5px;
		}
		#celeokno {
			display:flex;
			flex-direction: row;
			justify-content: space-around;
		}
		@media only screen and (max-width: 800px) {
		  #celeokno{
		  	width: 95% !important;
		  	overflow-x: hidden !important;
		  }
		  #mena {
		  	overflow-x: hidden !important;
		  }
		  #chat {
		  	overflow-x: hidden !important;
		  }
		}
	</style>
	<script type="text/javascript">
		var casovac;
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
	      <li class="nav-item active">
	        <a class="nav-link" href="" onclick="return false;">Messages</a>
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
		<div class="box" id="celeokno"  style="width:50%;">
			<div id="frlist" style="width:24%;overflow-y:scroll;">
				<ul class="list-group">
				<?php
						$ja = $_SESSION['name'];
						$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
						if ($conn->connect_error) {
				  			die("Connection failed: " . $conn->connect_error);
						}
						$sql = "SELECT hrcone.prezdivka AS 'prone',hrctwo.prezdivka AS 'prtwo' FROM pratele JOIN hraci AS hrcone ON pratele.nameone=hrcone.id JOIN hraci AS hrctwo ON pratele.nametwo=hrctwo.id WHERE (hrcone.prezdivka='$ja' OR hrctwo.prezdivka='$ja') AND status='1'";
						$result = $conn->query($sql);
						echo $conn->error;
						if($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								if($row['prone'] == $ja) {
									echo "<li class=\"list-group-item align-items-center\" style='cursor:pointer;' onclick='showmess(`".$row['prone']."`,`".$row['prtwo']."`,this,true);clearInterval(casovac);casovac = setInterval(function(){showmess(`".$row['prone']."`,`".$row['prtwo']."`,this,false)},1000);'><a>".$row['prtwo']."</a></li>";
								}else if($row['prtwo'] == $ja) {
									echo "<li style='cursor:pointer;' class=\"list-group-item align-items-center\" onclick='showmess(`".$row['prtwo']."`,`".$row['prone']."`,this,true);clearInterval(casovac);casovac = setInterval(function(){showmess(`".$row['prtwo']."`,`".$row['prone']."`,this,false)},1000);'><a>".$row['prone']."</a></li>";
								}
							}
						}
					?>
				</ul>	
			</div>
			<br/>
		<div id="mena" style="opacity:0;width:74%;">
			<div id="hlavicka" style="overflow-x:hidden;">

			</div><hr class="my-4">
			<div id="chat" style="height: 350px;overflow-y:scroll;">

			</div><br/><br/>
					<div class="form-inline" style='clear:both;padding:5px;' id="druhyform">
						<input type="text" id="textkod" style="width:80%;" class="form-control" autocomplete="off" placeholder="Type a message">&nbsp;
						<button class="btn btn-primary" onclick="odeslani();" style="width:19%;">Send</button>
					</div>
		</div>

		</div>
			<script type="text/javascript">
				var druhejuser = "";
				var prvniuser = "";
				var scrolling = 0;
				const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				$("#druhyform").submit(function(e) {
    				e.preventDefault();
				});
				Number.prototype.pad = function(size) {
				    var s = String(this);
				    while (s.length < (size || 2)) {s = "0" + s;}
				    return s;
				}
				function odeslani() {
					if(document.getElementById("textkod").value != "") {
						let promenna = document.getElementById("textkod").value;
						document.getElementById("textkod").value = "";
						$.ajax({
							url: './handlers/sendhandler.php',
							type: 'get',
							data: {
							"thisuser":prvniuser,
							"otheruser":druhejuser,
							"message":promenna
							},
							async: true,
							success: function(){
								showmess(prvniuser,druhejuser);
								var element = document.getElementById("chat");
								element.scrollTop = element.scrollHeight;	
							}
						});
					}
				}
				$("#chat").scroll(function(){
				    scrolling = 1;
				    var stopListener = $(window).mouseup(function(){
				        scrolling = 0;
				    });
				});

				function showmess(thisuser, otheruser,element,bulyn) {
					druhejuser = otheruser;
					prvniuser = thisuser;
					$("#mena").css("opacity","1");
					if(bulyn) {
						$('li').removeClass("active");
						$(element).addClass("active");
					}
					$("#hlavicka").html("<h2>"+otheruser+"</h2>");
					$.ajax({
						url: './handlers/fetchzpravy.php',
						type: 'get',
						data: {
						"thisuser":thisuser,
						"otheruser":otheruser
						},
						async: true,
						success: function(data){
							$("#chat").html("");
							if(data !="") {
								var neco = JSON.parse(data);
							console.log(neco);
							let date = new Date();
							let soucasny = new Date();
							let vypis = "";
							for(var i = neco.radek.length-1; i >= 0; i--) {
								date = new Date(Date.parse(neco.radek[i].datetime));
								let yyyy = date.getFullYear();
								let mm = date.getMonth()+1;
								let dd = date.getDate();
								if(date.getFullYear()-soucasny.getFullYear() != 0) {
									vypis = dd+"."+mm+"."+yyyy;
								}else {
									if(mm-(soucasny.getMonth()+1) != 0 || (dd-soucasny.getDate()) != 0) {
										vypis = dd+". "+months[mm-1];
									}else {
										vypis = (date.getHours()).pad()+":"+(date.getMinutes()).pad()+" Today";
									}
								}
								if(neco.radek[i].from == thisuser) {
									document.getElementById("chat").innerHTML+="<div  style='clear:both;float:right;width:50%;'><li style=';padding:1px;padding-left: 3px;margin:0;' class='list-group-item list-group-item-primary rounded zpravy'>"+neco.radek[i].zprava+"</li><span class='datum' style='float:left;'>"+vypis+"</span></div><br/><br/>";
								}else {
									document.getElementById("chat").innerHTML+="<div><li style='clear:both;float:left;width:50%;padding:1px;padding-left: 3px;margin:0;' class='list-group-item list-group-item-secondary rounded zpravy'>"+neco.radek[i].zprava+"</li><span class='datum' style='float:left;'>"+vypis+"</span></div><br/><br/>";
								}
							}
							}
							if(scrolling == 0) {
								let element = document.getElementById("chat");
								let neco1 = element.scrollHeight;
								$("#chat").animate({ scrollTop: neco1 },"slow");
							}
						}
					});
				}
			</script>
	</div>
</body>
</html>
<?php
	ob_flush();
?>