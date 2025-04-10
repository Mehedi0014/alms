<?php
	date_default_timezone_set("Asia/Dhaka");

	// $environment = 'production';
	$environment = 'localhost';

	if( $environment == 'production' ) {
		$servername = "localhost";
		$dBUsername = "yourtec1_alms_usr";
		$dbPassword = "{]PzQ%6~yHF,";
		$dBName = "yourtec1_alms_v1";
	} elseif ( $environment == 'localhost' ) {
		$servername = "localhost";
		$dBUsername = "root";
		$dbPassword = "";
		$dBName = "alms";
	}

	try {
		$conn = new PDO("mysql:host=$servername;dbname=".$dBName, $dBUsername, $dbPassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (\Exception $e) {
		die();
	}


	
	if ( $environment == 'production' ) {
		$baseurl = "https://alms.yourtechsolution.in/";
	} elseif ( $environment == 'localhost' ) {
		$baseurl = "http://localhost/alms";
	}
	


	function generateSanitize($input, $type = 'string'){
		$typeList = [
			'string' => FILTER_SANITIZE_STRING,
			'int' => FILTER_SANITIZE_NUMBER_INT,
			'float' => FILTER_SANITIZE_NUMBER_FLOAT,
			'email' => FILTER_VALIDATE_EMAIL,
		];
		$typeOf = $typeList[strtolower($type)];
		if($typeOf === NULL){
			$typeOf = $typeList['string'];
		}
		$res = filter_var($input, $typeOf);
		return $res;
	}
?>