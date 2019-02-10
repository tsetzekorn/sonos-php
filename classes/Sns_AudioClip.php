<?php
class Sns_AudioClip{
	private $connection;
	private $audioClipId;
	private $name;
	private $appId;
	private $priority;
	private $clipType;
	private $status;
	
	function __construct($audioClip) {
		$this->connection 			= New Sns_Connection;
		$this->audioClipId 			= $audioClip["id"];
		$this->name 				= $audioClip["name"];
		$this->appId     			= $audioClip["appId"];
		$this->priority 			= $audioClip["priority"];
		$this->clipType 			= $audioClip["clipType"];
		$this->status           	= $audioClip["status"];
	}
	
	public function getId() {
		return $this->audioClipId;
	}

	public function getName() {
		return $this->name;
	}
	
}
?>