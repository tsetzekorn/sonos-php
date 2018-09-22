<?php

$config = parse_ini_file(dirname(__FILE__)."/config.ini");

if (is_array($config) && !empty($config)) {

  if ($config["client_id"] == "" || $config["client_secret"] == "" || $config["redirect_uri"] == "") {
	  die("ERROR: Invalid config.ini provided!");
  }

  if (isset($_GET["code"]) && $_GET["code"] <> "") {
	echo "You have been successfully authorized.";

	$config["authcode"]			= $_GET["code"];
	$config["access_token"]		= "";
	$config["refresh_token"]	= "";
	$config["token_expires"]	= "";

	// Write update configuration values to config.ini
	$file = fopen(dirname(__FILE__)."/config.ini", "w");
	foreach ($config as $key => $value) {
		fwrite($file, $key . " = ". $value . PHP_EOL);	
	}		
	fclose($file);	
	
  } else {
	echo '
		<button onclick="location.href=\'https://api.sonos.com/login/v3/oauth?client_id='.$config["client_id"].'&response_type=code&state=getAuthorizationCode&scope=playback-control-all&redirect_uri='.urlencode($config["redirect_uri"]).'\'" type="button">
			Sign in with Sonos
		</button>
	';
  }

} else {
	die("ERROR: config.ini file does not exist.");
}
?>