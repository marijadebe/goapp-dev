			var userid = <?php 
				$mena = $_SESSION['name'];
				$conn = new mysqli('localhost', 'root', 'UUWhjrYYJpdKFVcn', 'gogame');
				if ($conn->connect_error) {
		  			die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT id FROM hraci WHERE prezdivka='$mena'";
				$result = $conn->query($sql);
				if($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					echo $_GET['id'].$row['id'];
				} 

			?>;
			var canvas = document.getElementById("platno");
			var ctx = canvas.getContext("2d");
			window.addEventListener('resize', resizeCanvas, false);
			resizeCanvas();
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
				var img = document.getElementById("bground");
				var pattern = ctx.createPattern(img, "repeat");
				ctx.beginPath();
				ctx.rect(0, 0, canvas.width, canvas.height);
				ctx.fillStyle = pattern;
				ctx.fill();
				ctx.closePath();
				ctx.beginPath();
				ctx.fillStyle = "black";
				ctx.arc(canvas.width/2, canvas.height/2, 5, 0, 2*Math.PI);
				ctx.fill();
				ctx.closePath();
				let krok = canvas.width/(gamestate.boardsize);
				for(var i = 0; i < gamestate.boardsize-1; i++) {
					for(var j = 0; j < gamestate.boardsize-1; j++) {
						ctx.beginPath();
						ctx.rect(i*krok+krok/2,j*krok+krok/2,krok,krok);
						ctx.stroke();
						ctx.closePath();
					}
				}
				let counteri = 0;
				let counterj = 0;		
				for(var i = 0; i < gamestate.boardstate.length; i++) {
					if(gamestate.boardstate[i] == "W") {
						ctx.beginPath();
						ctx.fillStyle = "white";						
						ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/2,0, 2*Math.PI);
						ctx.fill();
						ctx.strokeStyle = "black";
					   	ctx.stroke();
						ctx.closePath();
						counterj++;
					}else if(gamestate.boardstate[i] == "B") {
						ctx.beginPath();
						ctx.fillStyle = "black";
						ctx.arc(counterj*krok+krok/2,counteri*krok+krok/2, krok/2,0, 2*Math.PI);
						ctx.fill();
						ctx.closePath();
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
						counterj = 0;
					}
				}
			}
			var web_socket = new WebSocket("ws://localhost:3000");
			web_socket.onopen = function(event) {
				$('#loading-container').slideUp('slow');
				web_socket.send(JSON.stringify({"type":"firstcon","userid":userid}));
			};

			web_socket.onmessage = function(event) {
			  var datames = JSON.parse(event.data);
			  if(datames.type == "resignmessage") {
			  	console.log('funguje to');
			  	$('#resignmodal').modal();
			  	$('#resignmodal .modal-body').html("<h4><a href='diff.php?name="+datames.fromuser+"'>"+datames.fromuser+"</a> resigned.</h4>");
			  	diff = Math.abs(datames.fromrat-datames.fromratold);
			  	$('#resignmodal .modal-body').append(datames.fromuser+"'s rating: "+datames.fromratold+" <b style='color: red;'>-"+diff+"</b><br/>");
			  	$('#resignmodal .modal-body').append(datames.touser+"'s rating: "+datames.toratold+" <b style='color: green;'>+"+diff+"<br/>");
			  }
			  else if(datames.type == "chatmessage") {
			  	console.log(datames.fromuser);
			  	$('.chat-content').append("<b>"+datames.fromuser+":</b>&nbsp;"+datames.content+"<br/>");
			  	let element = document.getElementsByClassName("chat-content")[0];
				let neco1 = element.scrollHeight;
				$(".chat-content").scrollTop(neco1);
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
			function resign() {
				web_socket.send(JSON.stringify({"type":"resign","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>"}));
			}
			function sendmes() {
				let test = JSON.stringify({"type":"textmes","from":"<?php echo $_SESSION['name']; ?>","gameid":"<?php echo $_GET['id']; ?>","content":$('#chat-form-text').val()});
				web_socket.send(test);

			}