var canvas = document.getElementById("board");
var boardsize = 450;
		var ctx = canvas.getContext("2d");
		var gamedata = {
			blacksmove: true,
			blackscore: 0,
			whitescore: 0,
			passcounter: 0,
			poslednix: 0,
			posledniy: 0,
			velikost: 9,
			runningindex: 0 
		};
		var board = [
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"],
		["x","x","x","x","x","x","x","x","x"]
		];
		var boardsarr = [];
		let neco1 =  $.extend(true,[],board);
		boardsarr.push(neco1);
		var nsize = boardsize/(gamedata.velikost+1);
		init();
		function pass() {
			gamedata.passcounter++;
			if(gamedata.passcounter == 2) {
				if(gamedata.blackscore > gamedata.whitescore) {
					alert("Black wins! -"+(gamedata.blackscore-gamedata.whitescore));
				}
				else {
					alert("White wins! +"+(gamedata.whitescore-gamedata.blackscore));
				}
			}
		}

		function init() {
			$('#an_moves').html("");
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			for(var i =0; i < (gamedata.velikost-1); i++) {
				for(var j = 0; j < (gamedata.velikost-1); j++) {
					/*
					if(i == 8/2+2 && j == 8/2+2 || i == 8/2-2 && j == 8/2-2 || i == 8/2+2 && j == 8/2-2 || i == 8/2-2 && j == 8/2+2 || i == 4 && j == 4) {
						ctx.beginPath();
						ctx.arc(i*(boardsize/10)+(boardsize/10), j*(boardsize/10)+(boardsize/10), 4, 0, 2*Math.PI);
						ctx.fillStyle = "black";
						ctx.fill();
						ctx.closePath();
					} */
					if(i == (gamedata.velikost-1)/2 && j == (gamedata.velikost-1)/2) {
						ctx.beginPath();
						ctx.arc(i*(nsize)+(nsize), j*(nsize)+(nsize), 4, 0, 2*Math.PI);
						ctx.fillStyle = "black";
						ctx.fill();
						ctx.closePath();
					}
					ctx.beginPath();
					ctx.lineWidth = 2;
					ctx.rect(i*(nsize)+(nsize),j*(nsize)+(nsize), (nsize), (nsize));
					ctx.strokeStyle = "black";					
					ctx.stroke();
				}
			}
			ctx.closePath();
		}

		function draw() {
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			for(var i =0; i < (gamedata.velikost-1); i++) {
				for(var j = 0; j < (gamedata.velikost-1); j++) {
					/*
					if(i == 8/2+2 && j == 8/2+2 || i == 8/2-2 && j == 8/2-2 || i == 8/2+2 && j == 8/2-2 || i == 8/2-2 && j == 8/2+2 || i == 4 && j == 4) {
						ctx.beginPath();
						ctx.arc(i*(boardsize/10)+(boardsize/10), j*(boardsize/10)+(boardsize/10), 4, 0, 2*Math.PI);
						ctx.fillStyle = "black";
						ctx.fill();
						ctx.closePath();
					} */
					if(i == (gamedata.velikost-1)/2 && j == (gamedata.velikost-1)/2) {
						ctx.beginPath();
						ctx.arc(i*(nsize)+(nsize), j*(nsize)+(nsize), 4, 0, 2*Math.PI);
						ctx.fillStyle = "black";
						ctx.fill();
						ctx.closePath();
					}
					ctx.beginPath();
					ctx.lineWidth = 2;
					ctx.rect(i*(nsize)+(nsize),j*(nsize)+(nsize), (nsize), (nsize));
					ctx.strokeStyle = "black";					
					ctx.stroke();
				}
			}
			ctx.closePath();
			for(var i =0; i < (gamedata.velikost); i++) {
				for(var j = 0; j < (gamedata.velikost); j++) {
					ctx.beginPath();
					ctx.lineWidth = 2;
					if(board[i][j] != "x") {
						ctx.arc(i*(nsize)+(nsize), j*(nsize)+(nsize), (nsize/2), 0, 2*Math.PI);
						if(board[i][j] == "B") {
							ctx.fillStyle = "black";
						}
						else {
							ctx.fillStyle = "white";
						}
					    ctx.fill();
					    ctx.fillStyle = "black";
					    ctx.stroke();
					    ctx.closePath();
					}
				}
			}
			if(gamedata.runningindex == boardsarr.length-1) {
				ctx.beginPath();
				ctx.arc(gamedata.poslednix*(nsize)+(nsize), gamedata.posledniy*(nsize)+(nsize), (boardsize*3)/((gamedata.velikost+1)*10), 0, 2*Math.PI);
				ctx.strokeStyle = "red";
				ctx.stroke();
				ctx.closePath();
			}
		}

		function countscore(x,y,color) {
			let score = 0;
			board[x][y] = "Cx";
			if((x+1) < gamedata.velikost && board[x+1][y] != color && board[x+1][y]!="x" && board[x+1][y]!="Cx") {
		    	return 1000000000000;
		    }
		    if((y+1) < gamedata.velikost && board[x][y+1] != color && board[x][y+1]!="x" && board[x][y++]!="Cx") {
		    	return 1000000000000;
		    }
		    if((x-1) >= 0 && board[x-1][y] != color && board[x-1][y]!="x" && board[x-1][y]!="Cx") {
		    	return 1000000000000;
		    }
		    if((y-1) >= 0 && board[x][y-1] != color && board[x][y-1]!="x" && board[x][y-1]!="Cx") {
		    	return 1000000000000;
		    }

		    if((x+1) < gamedata.velikost && board[x+1][y] == "x") {
		    	score += countscore(x+1,y);
		    }
		    if((x-1) >= 0 && board[x-1][y] == "x") {
		    	score += countscore(x-+1,y);
		    }
		    if((y-1) >= 0 && board[x][y-1] == "x") {
		    	score += countscore(x,y-1);
		    }
		    if((y+1) < gamedata.velikost && board[x][y+1] == "x") {
		    	score += countscore(x,y+1);
		    }
		    score++;
		    return score;
		}

		function countliberties(x,y,color,firstit) {
				let liberties = 0;
					board[x][y] = "C"+color;
					if(x > 0 && board[x-1][y] == "x") {
						liberties++;
						board[x-1][y] = "Cx";
					} 
					if(y > 0 && board[x][y-1] == "x") {
						liberties++;
						board[x][y-1] = "Cx";
					} 
					if(x < (gamedata.velikost-1) && board[x+1][y] == "x") {
						liberties++;
						board[x+1][y] = "Cx";
					}
					if(y < (gamedata.velikost-1) && board[x][y+1] == "x") {
						liberties++;
						board[x][y+1] = "Cx";
					}
					if(((x+1) < gamedata.velikost) && board[x+1][y] == color) {
						liberties += countliberties(x+1,y,color,false);
					}
					if(((y+1) < gamedata.velikost) && board[x][y+1] == color) {
						liberties += countliberties(x,y+1,color,false);
					}
					if(((x-1)>= 0) && board[x-1][y] == color) {
						liberties += countliberties(x-1,y,color,false);
					}
					if(((y-1)>= 0) && board[x][y-1] == color) {
						liberties += countliberties(x,y-1,color,false);
					}
					if(firstit === true) {
						for(var i = 0; i < gamedata.velikost; i++) {
							for(var k = 0; k < gamedata.velikost; k++) {
								if(board[i][k].includes("C")) {
									board[i][k] = board[i][k].substring(1);
								}
							}
						}
					}
					return liberties;
		}
		function removestones(x,y,color) {
					board[x][y] = "x";
					if(color == "W") {
						gamedata.blackscore++;
						gamedata.whitescore--;
					}
					else if(color =="B") {
						gamedata.whitescore++;
						gamedata.blackscore--;
					}
					if(((x+1) < gamedata.velikost) && board[x+1][y] == color) {
						removestones(x+1,y,color);
					}
					if(((y+1) < gamedata.velikost) && board[x][y+1] == color) {
						removestones(x,y+1,color);
					}
					if(((y-1) >=0) && board[x][y-1] == color) {
						removestones(x,y-1,color);
					}
					if(((x-1) >=0) && board[x-1][y] == color) {
						removestones(x-1,y,color);
					}
		}
		function validate(rx,ry) {
			if(board[rx][ry] != "x" || (gamedata.runningindex != boardsarr.length-1)) {
				return 0;
			}
			if(gamedata.blacksmove == true) {
				board[rx][ry] = "B";
				barva = "B";antibarva = "W";
			}else if(gamedata.blacksmove == false) {
				board[rx][ry] = "W";
				barva = "W";antibarva = "B";
			}

			if(rx+1 < gamedata.velikost && board[rx+1][ry] == antibarva) {
				volnemisto = countliberties(rx+1,ry,antibarva,true);
				if(volnemisto == 0) {
					removestones(rx+1,ry,antibarva);
				}
			}
			if(ry+1 < gamedata.velikost && board[rx][ry+1] == antibarva) {
				volnemisto = countliberties(rx,ry+1,antibarva,true);
				if(volnemisto == 0) {
					removestones(rx,ry+1,antibarva);
				}
			}
			if((rx-1) >= 0 && board[rx-1][ry] == antibarva) {
				volnemisto = countliberties(rx-1,ry,antibarva,true);
				if(volnemisto == 0) {
					removestones(rx-1,ry,antibarva);
				}
			}
			if((ry-1) >= 0 && board[rx][ry-1] == antibarva) {
				volnemisto = countliberties(rx,ry-1,antibarva,true);
				if(volnemisto == 0) {
					removestones(rx,ry-1,antibarva);
				}
			}
			if(rx+1 < gamedata.velikost && board[rx+1][ry] == barva) {
				volnemisto = countliberties(rx+1,ry,barva,true);
				if(volnemisto == 0) {
					return 1;
				}
			}
			if(ry+1 < gamedata.velikost && board[rx][ry+1] == barva) {
				volnemisto = countliberties(rx,ry+1,barva,true);
				if(volnemisto == 0) {
					return 1;
				}
			}
			if(rx-1 >= 0 && board[rx-1][ry] == barva) {
				volnemisto = countliberties(rx-1,ry,barva,true);
				if(volnemisto == 0) {
					return 1;
				}
			}
			if(ry-1 >= 0 && board[rx][ry-1] == barva) {
				volnemisto = countliberties(rx,ry-1,barva,true);
				if(volnemisto == 0) {
					return 1;
				}
			}
			volnemisto = countliberties(rx,ry,barva, true);
			if(volnemisto == 0) {
				return 1;
			}

			var pismenko = String.fromCharCode(rx+65);
			var cislicko = gamedata.velikost-ry;
			if(boardsarr.length % 2 !=0) {
				$('#an_moves').append(parseInt(boardsarr.length/2+1)+". "+pismenko+cislicko+" ");
			}else {
				$('#an_moves').append(pismenko+cislicko+"<br/>");
			}

			let neco2 =  $.extend(true,[],board);
			boardsarr.push(neco2);
			gamedata.runningindex++;

			return 2;
		}
		function change(sizing) {
			board = Array.from({ length: sizing }, () => 
			  Array.from({ length: sizing }, () => false)
			);
			for (var i = 0; i < sizing; i++) {
				for (var j = 0; j < sizing; j++) {
					board[i][j] = "x";
				}
			}
			boardsarr = [];
			let neco3 =  $.extend(true,[],board);
			boardsarr.push(neco3);
			gamedata.runningindex = 0;
			gamedata = {
				blacksmove: true,
				blackscore: 0,
				whitescore: 0,
				passcounter: 0,
				poslednix: 0,
				posledniy: 0,
				velikost: sizing,
				runningindex: 0
			};
			nsize = boardsize/(gamedata.velikost+1);
			init();
		}
		function browse(what) {
			switch(what) {
				case 1:
					gamedata.runningindex = 0;
					break;
				case 4:
					gamedata.runningindex = boardsarr.length-1;
					break;
				case 2:
					if(gamedata.runningindex > 0) {
						gamedata.runningindex--;
					}
					break;
				case 3:
					if(gamedata.runningindex < boardsarr.length-1) {
						gamedata.runningindex++;
					}
					break;
			}
			board = $.extend(true,[],boardsarr[gamedata.runningindex]);
			draw();
		}

		canvas.addEventListener("mousedown", function(event) {
			let rect = canvas.getBoundingClientRect(); 
            let x = event.clientX - rect.left; 
            let y = event.clientY - rect.top;
            for(var i =1; i < (gamedata.velikost+1); i++) {
				for(var j = 1; j < (gamedata.velikost+1); j++) {
					if(y > j*(nsize)-(nsize/2) && x > i*(nsize)-(nsize/2) && y < j*(nsize)+(nsize/2) && x < i*(nsize)+(nsize/2)) {
						var xrecent = i-1;
						var yrecent = j-1;
					}
				}
			}
			result = validate(xrecent,yrecent);
			if(result == 1) {
				board[xrecent][yrecent] = "x";
			}else if(result == 2) {
				gamedata.blacksmove = !gamedata.blacksmove;
				gamedata.poslednix = xrecent;
				gamedata.posledniy = yrecent;
			}
			gamedata.passcounter = 0;
			draw();
		});