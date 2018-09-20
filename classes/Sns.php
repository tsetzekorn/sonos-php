<?php
class Sns {
	private $accountId;
	protected $connection;
	public $households = array();
	
	function __construct() {
		$this->connection = $this->getConnection();
		$this->getHouseholds();
	}
	
	public function getHouseholds($refresh=false) {
		if ($refresh || empty($this->households)) {
			$result = $this->connection->doRequest("/households",array(),array(),"GET");
		
			foreach ($result["households"] as $household) {
				$this->households[] = New Sns_Household($household);
			}
		}
		return $this->households;
	}
	
	protected function getConnection() {
		return New Sns_Connection();
	}
}
?>