<?php
set_time_limit(0);

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require_once './vendor/autoload.php';
require_once './socketuziv.php';
require_once './weiqi.php';
require_once './globaldata.php';

class GoApp implements MessageComponentInterface {
    protected $clients;
    private $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
    }
    static function stringToBoardArr($boardenc) {
        $boardarray = array(array());
        $counteri = 0;$counterj = 0;
        for ($i=0; $i < strlen($boardenc); $i++) { 
            if($boardenc[$i] == 'W' || $boardenc[$i] == 'B' || $boardenc[$i] == 'w' || $boardenc[$i] == 'b') {
                $boardarray[$counteri][$counterj] = $boardenc[$i];
                $counterj++;
            }else if($boardenc[$i] == '/') {
                $counteri++;
                $counterj = 0;
            }else {
                if(($i+1) < strlen($boardenc) &&  is_numeric($boardenc[$i+1])) {
                    $arr;
                    $arr[0] = $boardenc[$i];
                    $arr[1] = $boardenc[$i+1];
                    $string = implode($arr);
                    $max = $counterj+$string;
                    while($counterj < ($max)) {
                        $boardarray[$counteri][$counterj] = "x";
                        $counterj++;;
                    }
                    $i++;
                }else {
                    $max = $counterj+$boardenc[$i];
                    while($counterj < $max) {
                        $boardarray[$counteri][$counterj] = "x";
                        $counterj++;
                    }
                }
            }
        }
        return $boardarray;
    }

    function pass($idhry, $from) {
        $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM hry WHERE id='$idhry'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $secpass = 0;
        if($row['passcounter'] == '1') {
            $board = GoApp::stringToBoardArr($row['boardstate']);
            $model = new Weiqi($board, $row['boardsize']);
            $sql = "UPDATE hry SET passcounter='2',move=IF(move=1,0,1) WHERE id='$idhry'";
            $conn->query($sql);
            $secpass = 1;
        }else if($row['passcounter'] == 0) { 
            $sql = "UPDATE hry SET passcounter='1',move=IF(move=1,0,1) WHERE id='$idhry'";
            $conn->query($sql);
        }
        $conn->close();
        for ($i=0; $i < count($this->users); $i++) { 
            if(strpos($this->users[$i]->getRealid(), $idhry) === 0) { 
                foreach($this->clients as $client) {
                    if($client->resourceId == $this->users[$i]->getSocketid()) {
                        $client->send('{"type":"pass","fromuser":"'.$from.'","secpass":"'.$secpass.'"}');
                    }
                }
            }
        } 
    }
    function gameEnd($idhry) {
        $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT hry.boardsize AS 'boardsize',hry.boardstate AS 'boardstate',hry.whitescore AS 'whitescore',hry.blackscore AS 'blackscore',hry.komi AS 'komi',hry.whoisblack AS 'whoisblack',hrcone.prezdivka AS 'prone', hrctwo.prezdivka AS 'prtwo' FROM hry JOIN hraci AS hrcone ON hry.playerone=hrcone.id JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id WHERE hry.id='$idhry'";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $board = GoApp::stringToBoardArr($row['boardstate']);
            $model = new Weiqi($board, $row['boardsize']); 
            $arr = $model->score();
            $arr['whitescore'] += $row['whitescore'];
            $arr['blackscore'] += $row['blackscore'];
            $arr['whitescore'] += $row['komi'];
            $loser;
            if($arr['whitescore'] > $arr['blackscore']) {
                if($row['whoisblack'] == 0) {
                    $loser = $row['prone'];
                }else {
                    $loser = $row['prtwo'];
                }
            }else {
                if($row['whoisblack'] == 0) {
                    $loser = $row['prtwo'];
                }else {
                    $loser = $row['prone'];
                }
            }
            $ark = GoApp::calculateRating($idhry, $loser);
            for ($i=0; $i < count($this->users); $i++) { 
                    if(strpos($this->users[$i]->getRealid(), $idhry) === 0) {                    
                        foreach($this->clients as $client) {
                            if($client->resourceId == $this->users[$i]->getSocketid()) {
                                $client->send('{"type":"gameendmessage","winner":"'.$ark['winner'].'","winnereloold":"'.$ark['winnereloold'].'","winnerelo":"'.$ark['winnerelo'].'","losereloold":"'.$ark['losereloold'].'","loser":"'.$ark['loser'].'","whitescore":"'.$arr['whitescore'].'","blackscore":"'.$arr['blackscore'].'"}');
                            }
                     }
                }
            }

        }
        $conn->close();
    }
    static function calculateRating($gid, $loser) {
                $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $resignelo = 0;
                $winner = 0;
                $winnerelo = 0;
                $resigneloold = 0;
                $winnereloold = 0;
                $loser = $conn->real_escape_string($loser);
                $vyd = ""; $vydgp = "";
                $sql = "SELECT rating,id AS 'vyd',gp FROM hraci WHERE prezdivka='$loser'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {$row = $result->fetch_assoc();$resignelo = $row['rating'];$resigneloold = $row['rating'];$vyd=$row['vyd'];$vydgp=$row['gp'];}else {}
                $sql = "SELECT hrcone.prezdivka AS 'prone', hrctwo.prezdivka AS 'prtwo' FROM hry JOIN hraci AS hrcone ON hry.playerone=hrcone.id JOIN hraci AS hrctwo ON hry.playertwo=hrctwo.id WHERE hry.id=$gid";
                $result1 = $conn->query($sql);
                if($result1->num_rows > 0) {$row = $result1->fetch_assoc(); if($row['prone'] != $loser) {$winner = $row['prone'];}else {$winner = $row['prtwo'];}}
                $sql = "DELETE FROM hry WHERE id=$gid";
                $conn->query($sql);
                $sql = "SELECT rating,id AS 'vid',gp FROM hraci WHERE prezdivka='$winner'";
                $result = $conn->query($sql);
                $vid = ""; $vidgp = "";
                if($result->num_rows > 0) {$row = $result->fetch_assoc();$winnerelo = $row['rating'];$winnereloold= $row['rating'];$vid = $row['vid'];$vidgp=$row['gp'];$sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vid','1')";$conn->query($sql);}else {}
                $difference = abs($winnerelo-$resignelo);
                if($difference < 300) {
                        $winnerelo += 5;
                        $resignelo -= 5;
                }else {
                    if($winnerelo < $resignelo) {
                        $percent = ($resignelo/100);
                        $percent = intval($percent);
                        $winnerelo += $percent;
                        $resignelo -= $percent;
                    }else {
                        $winnerelo += 1;
                        $resignelo -= 1;
                    }
                }
                $sql = "";
                switch ($vidgp) {
                    case 1:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vid','2')";
                        break;
                    case 10:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vid','4')";
                        break;
                    case 25:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vid','5')";
                        break;
                }
                if($sql!=""){$conn->query($sql);}
                $sql = "";
                switch ($vydgp) {
                    case 1:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vyd','2')";
                        break;
                    case 10:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vyd','4')";
                        break;
                    case 25:
                        $sql = "INSERT INTO hraciuspechpropojovaci(id_hrace,id_uspech) VALUES ('$vyd','5')";
                        break;
                }
                if($sql!=""){$conn->query($sql);}
                $sql = "UPDATE hraci SET rating=$resignelo WHERE prezdivka='$loser'";
                $conn->query($sql);
                $sql = "UPDATE hraci SET rating=$winnerelo WHERE prezdivka='$winner'";
                $conn->query($sql);
                $conn->close();
                $arr = array(
                    "winner" => $winner,
                    "loser" => $loser,
                    "winnerelo" => $winnerelo,
                    "loserelo" => $resignelo,
                    "losereloold" => $resigneloold,
                    "winnereloold" => $winnereloold,
                );
                return $arr;
    }

    function moveHandler($whosmove, $idhry, $x, $y) {
        $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $sql = "SELECT boardstate,boardsize FROM hry WHERE id='$idhry'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $boardarray = GoApp::stringToBoardArr($row['boardstate']);
        $model = new Weiqi($boardarray, $row['boardsize']);
        $prom = $model->validate($x, $y,$whosmove);
        $boardstate = "";
        if($prom == 2) {
            $boardstate = $model->boardtocom();
            $sql = "UPDATE hry SET boardstate='$boardstate',passcounter='0', move=IF(move=1,0,1) WHERE id='$idhry'";
            $conn->query($sql);
            $score = $model->get_deadcounter();
            if($whosmove == 0) {
                $sql = "UPDATE hry SET whitescore=(whitescore+$score) WHERE id='$idhry'";
                $conn->query($sql);
            }else {
                $sql = "UPDATE hry SET blackscore=(blackscore+$score) WHERE id='$idhry'";
                $conn->query($sql);
            }
            $prom = count($this->users);
            for ($i=0; $i < $prom; $i++) { 
                if(strpos($this->users[$i]->getRealid(), $idhry) === 0) { 
                    foreach($this->clients as $client) {
                        if($client->resourceId == $this->users[$i]->getSocketid()) {
                            $client->send('{"type":"move","boardstate":"'.$boardstate.'","posx":'.$x.',"posy":'.$y.'}');
                        }
                    }
                }
            }     
        }
        $conn->close();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $data) {
        $data = json_decode($data);
        switch($data->type) {
        	case 'firstcon':
                $counter = 0;
                $hod = count($this->users);
                for ($i=0; $i < $hod; $i++) { 
                    if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {                    
                            $counter++;
                    }
                }
                array_push($this->users, new Uziv($data->userid,$from->resourceId,$data->gameid));
                for ($i=0; $i < count($this->users); $i++) { 
                    if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {                    
                        foreach($this->clients as $client) {
                            if($client->resourceId == $this->users[$i]->getSocketid() && $counter >= 1) {
                                $client->send('{"type":"liftweil"}');
                            }
                        }
                    }
                }
        		break;
        	case 'resign':
                  $arr = GoApp::calculateRating($data->gameid,$data->from);
				  for ($i=0; $i < count($this->users); $i++) { 
        			if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {        			
        				foreach($this->clients as $client) {
        						if($client->resourceId == $this->users[$i]->getSocketid()) {
        							$client->send('{"fromuser":"'.$data->from.'","touser":"'.$arr['winner'].'","type":"resignmessage","fromrat":"'.$arr['loserelo'].'","fromratold":"'.$arr['losereloold'].'","toratold":"'.$arr['winnereloold'].'"}');
        						}
        					}
        				}
        			}

        		break;
        	case 'textmes':
                $msg = htmlspecialchars($data->content, ENT_QUOTES, 'UTF-8');
        		for ($i=0; $i < count($this->users); $i++) { 
        			if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) { 
        				foreach($this->clients as $client) {
        					if($client->resourceId == $this->users[$i]->getSocketid()) {
        						$client->send('{"fromuser":"'.$data->from.'","content":"'.$msg.'","type":"chatmessage"}');
        					}
        				}
        			}
        		}     
        		break;   
            case 'move':
                GoApp::moveHandler($data->color, $data->gameid, $data->posx, $data->posy);
                break;
            case 'pass':
                GoApp::pass($data->gameid, $data->from);
                break;		
            case 'deadsel':
                $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                $idhry = $data->gameid;
                $sql = "SELECT boardstate,boardsize FROM hry WHERE id='$idhry'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $boardarray = GoApp::stringToBoardArr($row['boardstate']);
                    $model = new Weiqi($boardarray, $row['boardsize']);
                    $model->selectdead($data->posx,$data->posy);
                    $boardstate = $model->boardtocom();
                    $sql = "UPDATE hry SET boardstate='$boardstate',passcounter='2' WHERE id='$idhry'";
                    $conn->query($sql);
                    for ($i=0; $i < count($this->users); $i++) { 
                        if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {                    
                            foreach($this->clients as $client) {
                                if($client->resourceId == $this->users[$i]->getSocketid()) {
                                    $client->send('{"type":"deadselcallback","boardstate":"'.$boardstate.'"}');
                                }
                            }
                        }
                    }
                }
                $conn->close();
                break;
            case 'acceptscore':
                $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                $idhry = $data->gameid;
                $sql = "SELECT passcounter FROM hry WHERE id='$idhry'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if($row['passcounter'] == 2) {
                        $sql = "UPDATE hry SET passcounter='3' WHERE id='$idhry'";
                        $conn->query($sql);
                        for ($i=0; $i < count($this->users); $i++) { 
                            if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {                    
                                foreach($this->clients as $client) {
                                    if($client->resourceId == $this->users[$i]->getSocketid()) {
                                        $client->send('{"type":"acceptcallback","from":"'.$data->from.'"}');
                                    }
                                }
                            }
                        }
                    }else if($row['passcounter'] == 3) {
                        GoApp::gameEnd($data->gameid);
                    }
                }
                $conn->close();
                break;
            case 'savetime':
                $conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                $blacktime = $data->blacktime;
                $whitetime = $data->whitetime;
                $gid = $data->gameid;
                $sql = "UPDATE hry SET whitetime='$whitetime', blacktime='$blacktime' WHERE id='$gid'";
                $conn->query($sql);
                $conn->close();
                break;
            case 'timeloss':
                $gid = $data->gameid;
                $arc = GoApp::calculateRating($gid, $data->from);
                $hod = count($this->users);
                for ($i=0; $i < $hod; $i++) { 
                    if(strpos($this->users[$i]->getRealid(), $data->gameid) === 0) {                    
                        foreach($this->clients as $client) {
                            if($client->resourceId == $this->users[$i]->getSocketid()) {
                                $client->send('{"type":"timelossmessage","fromuser":"'.$data->from.'","touser":"'.$arc['winner'].'","fromrat":"'.$arc['loserelo'].'","fromratold":"'.$arc['losereloold'].'","toratold":"'.$arc['winnereloold'].'"}');
                            }
                        }
                    }
                }
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $hod = count($this->users);
        $neco;
    	for($i = 0; $i < $hod; $i++) {
            if ($conn->resourceId == $this->users[$i]->getSocketid()) {
                $neco = $this->users[$i]->getGameid();
                unset($this->users[$i]);
                $this->users = array_values($this->users);
                break;
            }
        }
        $hod = count($this->users);
        for ($i=0; $i < $hod; $i++) { 
            if(strpos($this->users[$i]->getRealid(), $neco) === 0) {                    
                    foreach($this->clients as $client) {
                        if($client->resourceId == $this->users[$i]->getSocketid()) {
                            $client->send('{"type":"putbackweil"}');
                        }
                    }
            }
        }
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
$server = IoServer::factory(new HttpServer(new WsServer(new GoApp())), g_WSPORT);
$server->run();

?>