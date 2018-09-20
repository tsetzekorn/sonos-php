<?php
class Sns_Favorite{
	private $connection;
	private $favoriteId;
	private $name;
	private $description;
	private $type;
	private $imageUrl;
	private $imageCompilation = array();
	private $service = array();
	
	
	function __construct($favorite) {
		$this->connection 			= New Sns_Connection;
		$this->favoriteId 			= $favorite["id"];
		$this->name 				= $favorite["name"];
		$this->description 			= $favorite["description"];
		$this->type 				= $favorite["type"];
		$this->imageUrl 			= $favorite["imageUrl"];
		$this->imageCompilation 	= $favorite["imageCompilation"];
		$this->service 				= $favorite["service"];
	}
	
	public function getId() {
		return $this->favoriteId;
	}

	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
}
?>