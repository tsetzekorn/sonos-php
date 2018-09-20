<?php
class Sns_Group{
	private $connection;
	private $groupId;
	private $name;
	private $playbackState;
	private $coordinatorId;
	private $playerIds;
	
	function __construct($group) {
		$this->connection 		= New Sns_Connection;
		$this->groupId 			= $group["id"];
		$this->name 			= $group["name"];
		$this->playbackState 	= $group["playbackState"];
		$this->coordinatorId 	= $group["coordinatorId"];
		$this->playerIds 		= $group["playerIds"];
	}
	
	public function getId() {
		return $this->groupId;
	}

	public function getName() {
		return $this->name;
	}

	public function getPlaybackState() {
		return $this->playbackState;
	}
	
	public function getPlayerIds() {
		return $this->playerIds;
	}
	
	public function play() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/play",array(),array(),"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}

	public function pause() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/pause",array(),array(),"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}

	public function togglePlayPause() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/togglePlayPause",array(),array(),"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	public function setVolume($volume) {
		if (intval($volume) > 100) {
			$body["volume"] = 100;
		} elseif (intval($volume) < 0) {
			$body["volume"] = 0;
		} else {
			$body["volume"] = intval($volume);
		}
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groupVolume",array(),$body,"POST");
		
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
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groupVolume/relative",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}	
	
	public function getVolume() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groupVolume",array(),array(),"GET");
		
		if ($result["statusCode"] == 200) {
			return $result["volume"];
		} else {
			return false;
		}
	}

	public function setMute($muted=true) {
		$body["muted"] = boolval($muted);
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groupVolume/mute",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}

	public function getMute() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groupVolume",array(),array(),"GET");
		
		if ($result["statusCode"] == 200) {
			return $result["muted"];
		} else {
			return false;
		}
	}
	
	private function setPlayModes($mode,$state) {
		$body["playModes"][$mode] = $state;
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/playMode",array(),$body,"POST");

		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	public function setShuffle($state=true){
		return $this->setPlayModes("shuffle",boolval($state));
	}
	
	public function setCrossfade($state=true){
		return $this->setPlayModes("crossfade",boolval($state));
	}
	
	public function setRepeat($state=true){
		return $this->setPlayModes("repeat",boolval($state));
	}
	
	public function setRepeatOne($state=true){
		return $this->setPlayModes("repeatOne",boolval($state));
	}
	
	public function skipToNextTrack() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/skipToNextTrack",array(),array(),"POST");

		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}		
	}

	public function skipToPreviousTrack() {
		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/skipToPreviousTrack",array(),array(),"POST");

		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}		
	}

	private function createSession($description="") {
		$body["appContext"] = "1234";
		$body["appId"] = "1234";
		$body["customData"] = $description;

		$result = $this->connection->doRequest("/groups/".$this->groupId."/playbackSession/joinOrCreate",array(),$body,"POST");

		if ($result["statusCode"] == 200) {
			return $result["sessionId"];
		} else {
			return false;
		}
	}
	
	public function loadFavorite($favoriteId,$play=false){
		$body["favoriteId"] 		= $favoriteId;
		$body["playOnCompletion"] 	= $play;

		$result = $this->connection->doRequest("/groups/".$this->groupId."/favorites",array(),$body,"POST");

		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	public function loadStreamUrl($url,$description=""){
		$sessionId = $this->createSession($description);
		
		if ($sessionId === false) {
			return false;
		} else {
			$body["streamUrl"] = $url;
			
			$result = $this->connection->doRequest("/playbackSessions/".$sessionId."/playbackSession/loadStreamUrl",array(),$body,"POST");

			if ($result["statusCode"] == 200) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function loadLineIn($deviceId,$playOnCompletion=false) {
		$body["deviceId"] 			= $deviceId;
		$body["playOnCompletion"] 	= $playOnCompletion;

		$result = $this->connection->doRequest("/groups/".$this->groupId."/playback/lineIn",array(),$body,"POST");

		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	public function setGroupMembers($playerIds=array()) {
		$body["playerIds"] = $playerIds;
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groups/setGroupMembers",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}		
	}

	public function modifyGroupMembers($playerIdsToAdd=array(),$playerIdsToRemove=array()) {
		$body["playerIdsToAdd"] = $playerIdsToAdd;
		$body["playerIdsToRemove"] = $playerIdsToRemove;
		
		$result = $this->connection->doRequest("/groups/".$this->groupId."/groups/modifyGroupMembers",array(),$body,"POST");
		
		if ($result["statusCode"] == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addGroupMembers($playerIdsToAdd=array()) {
		return $this->modifyGroupMembers($playerIdsToAdd,array());
	}
	
	public function removeGroupMembers($playerIdsToRemove=array()) {
		return $this->modifyGroupMembers(array(),$playerIdsToRemove);
	}
}
?>