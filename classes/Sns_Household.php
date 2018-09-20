<?php
class Sns_Household{
	private $connection;
	private $householdId;
	private $name;
	
	public  $players 	= array();
	public  $groups 	= array();
	public  $favorites 	= array();
	
	function __construct($household) {
		$this->connection = New Sns_Connection;
		$this->householdId = $household["id"];
		$this->name = $household["name"];
		$this->updateEnvironment();
	}
	
	public function getId() {
		return $this->householdId;
	}

	public function getName() {
		return $this->name;
	}
	
	public function updateEnvironment() {
		$result = $this->connection->doRequest("/households/".$this->householdId."/groups",array(),array(),"GET");
		
		$this->players = array();
		foreach ($result["players"] as $player) {
			$this->players[] = New Sns_Player($player);
		}
		
		$this->groups = array();
		foreach ($result["groups"] as $group) {
			$this->groups[] = New Sns_Group($group);
		}
		
		$result = $this->connection->doRequest("/households/".$this->householdId."/favorites",array(),array(),"GET");

		$this->favorites = array();
		foreach ($result["items"] as $favorite) {
			$this->favorites[] = New Sns_Favorite($favorite);
		}

		return true;
	}
	
	public function createGroup($players){
		$playerIds["playerIds"] = $players;
		$result = $this->connection->doRequest("/households/".$this->householdId."/groups/createGroup",array(),$playerIds,"POST");
		
		if ($result["statusCode"] == 200) {
			$group = New Sns_Group($result["group"]);
			$this->updateEnvironment();
			return $group;
		} else {
			return false;
		}
	}
	
	public function getAllPlayers() {
		return $this->players;
	}
	
	public function getPlayerById($id) {
		foreach ($this->players as $player) {
			if ($player->getId() == $id) {
				return $player;
			}
		}
		return false;
	}
	
	public function getPlayerByName($name,$bestmatch=false) {
		$best = 0;
		
		foreach ($this->players as $player) {
			if ($player->getName() == $name) {
				return $player;
			}
			
			if ($bestmatch) {
				similar_text ( strtoupper($name) , strtoupper($player->getName()) , $similarity );
				if ($similarity > $best) {
					$best = $similarity;
					$bestplayer = $player;
				}	
			}
		}
		if ($bestmatch) {
			return $bestplayer;
		} else {
			return false;
		}
	}
	
	public function getAllGroups() {
		return $this->groups;
	}

	public function getGroupById($id) {
		foreach ($this->groups as $group) {
			if ($group->getId() == $id) {
				return $group;
			}
		}
		return false;
	}
	
	public function getGroupByPlayerId($playerId) {
		foreach ($this->groups as $group) {
			if (in_array($playerId, $group->getPlayerIds())) {
				return $group;
			}
		}
		return false;		
	}
	
	public function getAllFavorites() {
		return $this->favorites;
	}

	public function getFavoriteById($id) {
		foreach ($this->favorites as $favorite) {
			if ($favorite->getId() == $id) {
				return $favorite;
			}
		}
		return false;
	}
	
	public function getFavoriteByName($name,$bestmatch=true) {
		$best = 0;
		
		foreach ($this->favorites as $favorite) {
			if ($favorite->getName() == $name) {
				return $favorite;
			}
			
			if ($bestmatch) {
				similar_text ( strtoupper($name) , strtoupper($favorite->getName()) , $similarity );
				if ($similarity > $best) {
					$best = $similarity;
					$bestfavorite = $favorite;
				}	
			}
		}
		if ($bestmatch) {
			return $bestfavorite;
		} else {
			return false;
		}
	}	
}
?>