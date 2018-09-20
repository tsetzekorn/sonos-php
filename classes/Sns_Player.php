<?php
class Sns_Player{
	private $connection;
	private $playerId;
	private $name;
	
	function __construct($player) {
		$this->connection = New Sns_Connection;
		$this->playerId = $player["id"];
		$this->name = $player["name"];
	}

	public function setVolume($volume) {
		if (intval($volume) > 100) {
			$body["volume"] = 100;
		} elseif (intval($volume) < 0) {
			$body["volume"] = 0;
		} else {
			$body["volume"] = intval($volume);
		}
		
		$result = $this->connection->doRequest("/players/".$this->playerId."/playerVolume",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}	

	public function setRelativeVolume($delta) {
		if (intval($delta) > 100) {
			$body["volumeDelta"] = 100;
		} elseif (intval($delta) < -100) {
			$body["volumeDelta"] = -100;
		} else {
			$body["volumeDelta"] = intval($delta);
		}
		
		$result = $this->connection->doRequest("/players/".$this->playerId."/playerVolume/relative",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}	
	
	public function getVolume() {
		$result = $this->connection->doRequest("/players/".$this->playerId."/playerVolume",array(),array(),"GET");
		
		if ($result["statusCode"] == 200) {
			return $result["volume"];
		} else {
			return false;
		}
	}

	public function setMute($muted=true) {
		$body["muted"] = boolval($muted);
		
		$result = $this->connection->doRequest("/players/".$this->playerId."/playerVolume/mute",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}

	public function getMute() {
		$result = $this->connection->doRequest("/players/".$this->playerId."/playerVolume",array(),array(),"GET");
		
		if ($result["statusCode"] == 200) {
			return $result["muted"];
		} else {
			return false;
		}
	}
	
	public function getId() {
		return $this->playerId;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function loadHomeTheaterPlayback() {
		$result = $this->connection->doRequest("/players/".$this->playerId."/homeTheater",array(),array(),"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}		
	}
}
?>