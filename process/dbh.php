<?php
	date_default_timezone_set("Asia/Dhaka");
	$servername = "localhost";
    $dBUsername = "yourtec1_alms_usr";
    $dbPassword = "{]PzQ%6~yHF,";
    $dBName = "yourtec1_alms_v1";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=".$dBName, $dBUsername, $dbPassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (\Exception $e) {
		die();
	}
	$baseurl = "https://alms.yourtechsolution.in/";
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