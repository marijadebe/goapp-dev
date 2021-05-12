<?php

class Uziv {
	private $realid;
	private $socketid;
    private $gameid;
	public function __construct($realid, $socketid, $gid) {
        $this->realid = $realid;
        $this->socketid = $socketid;
        $this->gameid = $gid;
    }

    public function getRealid() {
    	return $this->realid;
    }
    public function getSocketid() {
    	return $this->socketid;
    }
    public function getGameid() {
        return $this->gameid;
    }
    public function setRealid($realid) {
    	$this->realid = $realid;
    }
    public function setSocketid($socketid) {
    	$this->socketid = $socketid;
    }
    public function setGameid($gameid) {
        $this->gameid = $gameid;
    }


}

?>