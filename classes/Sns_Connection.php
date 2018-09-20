<?php
class Sns_Connection{
    private $access_token;
	private $refresh_token;
	private $authcode;
	private $client_id;
	private $client_secret;

	private $apihost;
	private $postheader;
	private $poststring;
	
	function __construct() {
		// Get Configuration via file
		$config = parse_ini_file(substr(dirname(__FILE__),0,strrpos(dirname(__FILE__),"/"))."/config.ini");

		$this->client_id 		= $config["client_id"];
		$this->client_secret 	= $config["client_secret"];
		$this->authcode 		= $config["authcode"];
		$this->access_token		= $config["access_token"];
		$this->refresh_token	= $config["refresh_token"];
		$this->token_expires	= $config["token_expires"];
		$this->redirect_uri		= $config["redirect_uri"];
		
		if ($this->access_token <> "") {
			if ($this->token_expires < time()) {
				$this->getAccessToken(true);
			} 
		} else {
			$this->getAccessToken(false);
		}
		$this->apihost			= 'https://api.ws.sonos.com/control/api/v1';	
	}

	private function getAccessToken($refresh=false){
		if ($refresh) {
			$param["grant_type"]   = 'refresh_token';
			$param["refresh_token"]= $this->refresh_token;			
		} else {
			$param["grant_type"]   = 'authorization_code';
			$param["code"]         = $this->authcode;
			$param["redirect_uri"] = $this->redirect_uri;
		}
		
		$result = $this->doRequest('https://api.sonos.com/login/v3/oauth/access',$param,array(),'POST');
		
		if ($result["statusCode"] == 200) {
			$this->access_token		= $result["access_token"];
			$this->refresh_token	= $result["refresh_token"];
			$this->token_expires	= intval($result["expires_in"]) + time();
			$this->updateConfig();
			return true;
		} else {
			return false;
		}
	}
	
	private function updateConfig(){
		$config = parse_ini_file(substr(dirname(__FILE__),0,strrpos(dirname(__FILE__),"/"))."/config.ini");
		$config["access_token"] 	= $this->access_token;
		$config["refresh_token"]	= $this->refresh_token;
		$config["token_expires"]	= $this->token_expires;
		
		$file = fopen(substr(dirname(__FILE__),0,strrpos(dirname(__FILE__),"/"))."/config.ini", "w");
		foreach ($config as $key => $value) {
			fwrite($file, $key . " = ". $value . PHP_EOL);	
		}		
		fclose($file);		
		
		return $config;
	}
	
    public function doRequest($path,$param=array(),$body=array(),$requesttype='GET') {
		if (empty($param)) {
			$this->postheader	= array('Content-Type: application/json');
		} else {
			$this->postheader	= array('Content-Type: application/x-www-form-urlencoded;charset=utf-8');
		}

		$ch = curl_init();

		if (strpos($path,"http://") === false && strpos($path,"https://") === false) {
			$path = $this->apihost . $path;
			$this->postheader[] = 'Authorization: Bearer ' . $this->access_token;
		} else {
			$this->postheader[] = 'Authorization: Basic '.base64_encode($this->client_id.':'.$this->client_secret);
		}
		curl_setopt($ch, CURLOPT_URL, $path);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requesttype);                                                                   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->postheader);
		
		if ($requesttype == "POST" && !empty($param)) {
			$querystring = http_build_query($param);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
		}
		if ($requesttype == "POST" && !empty($body)) {
			$poststring = json_encode($body);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		}
		
		$result = json_decode(curl_exec($ch),true);
		$result["statusCode"] = curl_getinfo($ch,CURLINFO_HTTP_CODE);

		curl_close($ch);

/*		
 		echo "<pre>";
		echo $path."\n";
		echo $requesttype."\n";
		print_r($this->postheader)."\n";
		echo $querystring."\n";
		echo $poststring."\n";
		print_r($result)."\n";
		echo "</pre>";
		echo "<hr>";
*/		
		return $result;
    }
	
}

?>