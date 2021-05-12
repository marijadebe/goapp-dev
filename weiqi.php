<?php
                                             
class Weiqi {
	public $board;
  public $boardsize;
  public $deadcounter;

	  function __construct($board,$boardsize) {
      $this->boardsize = $boardsize;
      $this->board = $board;
      $this->deadcounter = 0;
  	}
    function set_board($board) {
      $this->board = $board;
    }
    function get_board() {
      return $this->board;
    }
    function get_deadcounter() {
      return $this->deadcounter;
    }
  	function countliberties($x,$y,$color,$firstit) {
  		$liberties = 0;
  		$this->board[$x][$y] = "C".$color;
  		if($x > 0 && $this->board[$x-1][$y] == "x") {
  			$liberties++;
  			$this->board[$x-1][$y] = "Cx";
  		}
  		if($y > 0 && $this->board[$x][$y-1] == "x") {
			$liberties++;
  			$this->board[$x][$y-1] = "Cx";
  		}
  		if($y < ($this->boardsize-1) && $this->board[$x][$y+1] == "x") {
			$liberties++;
  			$this->board[$x][$y+1] = "Cx";
  		}
  		if($x < ($this->boardsize-1) && $this->board[$x+1][$y] == "x") {
			$liberties++;
  			$this->board[$x+1][$y] = "Cx";
  		}

  		if((($x+1) < $this->boardsize) && $this->board[$x+1][$y] == $color) {
  			$liberties += Weiqi::countliberties($x+1,$y,$color,FALSE);
  		}
  		if((($y+1) < $this->boardsize) && $this->board[$x][$y+1] == $color) {
  			$liberties += Weiqi::countliberties($x,$y+1,$color,FALSE);
  		}
  		if((($x-1) >= 0) && $this->board[$x-1][$y] == $color) {
  			$liberties += Weiqi::countliberties($x-1,$y,$color,FALSE);
  		}
  		if((($y-1) >= 0) && $this->board[$x][$y-1] == $color) {
  			$liberties += Weiqi::countliberties($x,$y-1,$color,FALSE);
  		}
  		if($firstit === TRUE) {
  			for ($i=0; $i < $this->boardsize; $i++) { 
  				for ($k=0; $k < $this->boardsize; $k++) { 
  					$pos = strpos($this->board[$i][$k], "C");
  					if($pos !== FALSE) {
  						$this->board[$i][$k] = substr($this->board[$i][$k],1);
  					}
  				}
  			}
  		}
  		return $liberties;
  	}
  	function removestones($x,$y,$color) {
  		$this->board[$x][$y] = "x";
      $this->deadcounter++;

  		if((($x+1) < $this->boardsize) && $this->board[$x+1][$y] == $color) {
  			Weiqi::removestones($x+1,$y,$color);
  		}
  		if((($y+1) < $this->boardsize) && $this->board[$x][$y+1] == $color) {
  			Weiqi::removestones($x,$y+1,$color);
  		}
  		if((($x-1) >= 0) && $this->board[$x-1][$y] == $color) {
  			Weiqi::removestones($x-1,$y,$color);
  		}
  		if((($y-1) >= 0) && $this->board[$x][$y-1] == $color) {
  			Weiqi::removestones($x,$y-1,$color);
  		}
  	}
    function selectdead($x, $y) {
      if($this->board[$x][$y] != "x") {
        $promenna = $this->board[$x][$y];
        if(preg_match('~^\p{Lu}~u', $this->board[$x][$y])) {
            $this->board[$x][$y] = strtolower($this->board[$x][$y]);
        }else {
            $this->board[$x][$y] = strtoupper($this->board[$x][$y]);
        }
        if((($x+1) < $this->boardsize) && $this->board[$x+1][$y] == $promenna) {
          Weiqi::selectdead($x+1,$y);
        }
        if((($y+1) < $this->boardsize) && $this->board[$x][$y+1] == $promenna) {
          Weiqi::selectdead($x,$y+1);
        }
        if((($x-1) >= 0) && $this->board[$x-1][$y] == $promenna) {
          Weiqi::selectdead($x-1,$y);
        }
        if((($y-1) >= 0) && $this->board[$x][$y-1] == $promenna) {
          Weiqi::selectdead($x,$y-1);
        }
      }
    }
    function validate($x, $y, $whosmove) {
        $this->deadcounter = 0;
        if($this->board[$x][$y] != "x") {
          return 0;
        }
        $anticolor = "";
        if($whosmove == "1") {
            $this->board[$x][$y] = "B";
            $anticolor = "W";
        }else {
            $this->board[$x][$y] = "W";
            $anticolor = "B";
        }
        if(($x+1) < $this->boardsize && $this->board[$x+1][$y] == $anticolor) {
            $emptyspace = Weiqi::countliberties($x+1,$y,$anticolor,TRUE);
            if($emptyspace == 0) {
              Weiqi::removestones($x+1,$y,$anticolor);
            }
        }
        if(($y+1) < $this->boardsize && $this->board[$x][$y+1] == $anticolor) {
            $emptyspace = Weiqi::countliberties($x,$y+1,$anticolor,TRUE);
            if($emptyspace == 0) {
              Weiqi::removestones($x,$y+1,$anticolor);
            }
        }
        if(($x-1) >= 0 && $this->board[$x-1][$y] == $anticolor) {
            $emptyspace = Weiqi::countliberties($x-1,$y,$anticolor,TRUE);
            if($emptyspace == 0) {
              Weiqi::removestones($x-1,$y,$anticolor);
            }
        }
        if(($y-1) >= 0 && $this->board[$x][$y-1] == $anticolor) {
           $emptyspace = Weiqi::countliberties($x,$y-1,$anticolor,TRUE);
           if($emptyspace == 0) {
              Weiqi::removestones($x,$y-1,$anticolor);
          }
        }
        if(($x+1) < $this->boardsize && $this->board[$x+1][$y] == $this->board[$x][$y]) {
          $emptyspace = Weiqi::countliberties($x+1,$y,$this->board[$x][$y],TRUE);
          if($emptyspace == 0) {
             return 1;
           }
        }
        if(($y+1) < $this->boardsize && $this->board[$x][$y+1] == $this->board[$x][$y]) {
          $emptyspace = Weiqi::countliberties($x,$y+1,$this->board[$x][$y],TRUE);
          if($emptyspace == 0) {
             return 1;
          }
        }  
        if(($x-1) >= 0 && $this->board[$x-1][$y] == $this->board[$x][$y]) {
          $emptyspace = Weiqi::countliberties($x-1,$y,$this->board[$x][$y],TRUE);
          if($emptyspace == 0) {
             return 1;
          }
        }
        if(($y-1) >= 0 && $this->board[$x][$y-1] == $this->board[$x][$y]) {
          $emptyspace = Weiqi::countliberties($x,$y-1,$this->board[$x][$y],TRUE);
          if($emptyspace == 0) {
             return 1;
          }
        }

        $emptyspace = Weiqi::countliberties($x,$y,$this->board[$x][$y], TRUE);
        if($emptyspace == 0) {
          return 1;
        }
        return 2;
    }
    function scoreCounter($x,$y,$color,$firstit) {
        $counter = 0;
        if($this->board[$x][$y] === 'x') {
          $counter++;
          $this->board[$x][$y] = 'X';
        }

        if((($x+1) < $this->boardsize) && $this->board[$x+1][$y] === $color) {
          $counter = -99999999;
        }
        else if((($y+1) < $this->boardsize) && $this->board[$x][$y+1] === $color) {
          $counter = -99999999;
        }
        else if((($x-1) >= 0) && $this->board[$x-1][$y] === $color) {
          $counter = -99999999;
        }
        else if((($y-1) >= 0) && $this->board[$x][$y-1] === $color) {
          $counter = -99999999;
        }

        if((($x+1) < $this->boardsize) && $this->board[$x+1][$y] === 'x') {
          $counter += Weiqi::scoreCounter($x+1,$y,$color,FALSE);
        }
        if((($y+1) < $this->boardsize) && $this->board[$x][$y+1] === 'x') {
          $counter += Weiqi::scoreCounter($x,$y+1,$color,FALSE);
        }
        if((($x-1) >= 0) && $this->board[$x-1][$y] === 'x') {
          $counter += Weiqi::scoreCounter($x-1,$y,$color,FALSE);
        }
        if((($y-1) >= 0) && $this->board[$x][$y-1] === 'x') {
          $counter += Weiqi::scoreCounter($x,$y-1,$color,FALSE);
        }
        if($firstit === TRUE) {
          for ($i=0; $i < $this->boardsize; $i++) { 
             for ($j=0; $j < $this->boardsize; $j++) { 
                 if($this->board[$i][$j] === 'X') {
                    $this->board[$i][$j] = 'x';
                 }
             }
           }
        }
        return $counter;
    }

    function score() {
      $whitescore = 0;
      $blackscore = 0;
      for ($i=0; $i < $this->boardsize; $i++) { 
        for ($j=0; $j < $this->boardsize; $j++) { 
          if($this->board[$i][$j] === 'x') {
              $promenna = Weiqi::scoreCounter($i,$j,'B',TRUE);
              echo $promenna."\n";
              if($promenna > 0) {
                $whitescore += $promenna;
              }
              $promenna = Weiqi::scoreCounter($i,$j,'W',FALSE);
              echo $promenna."\n";
              if($promenna > 0) {
                $blackscore += $promenna;
              }
              
                for ($x=0; $x < $this->boardsize; $x++) { 
                   for ($y=0; $y < $this->boardsize; $y++) { 
                       if($this->board[$x][$y] === 'X') {
                          $this->board[$x][$y] = 'c';
                       }
                   }
                 }
              Weiqi::vypisarr();
          }
        }
      }
      $arr = array(
          "whitescore" => $whitescore,
          "blackscore" => $blackscore,
      );

      return $arr;
    }
    function vypisarr() {
            for ($i=0; $i < $this->boardsize; $i++) { 
                for ($j=0; $j < $this->boardsize; $j++) { 
                    echo $this->board[$i][$j].", ";
                }
                echo "\n";
              }
    }

    function boardtocom() {
      $string = "";
      for ($i=0; $i < $this->boardsize; $i++) { 
        $counter = 0;
        for ($j=0; $j < $this->boardsize; $j++) { 
          if($this->board[$i][$j] == "W" || $this->board[$i][$j] == "B" || $this->board[$i][$j] == "w" || $this->board[$i][$j] == "b") {
            if($counter != 0) {
              $string .= $counter;
            }
            $counter = 0;
            $string .= $this->board[$i][$j];
          }elseif ($this->board[$i][$j] == "x") {
            $counter++;
          }
        }
        if($counter != 0) {
          $string .= $counter;
        }
        $string .= "/";
        $counter = 0;
      }
      $string = substr($string, 0, -1);
      return $string;
    }



}

?>