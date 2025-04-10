<?php
  session_start();
  if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    if($_SESSION['role'] === 'user'){
      header('Location: index.php');
      exit;
    }
    require_once ('dbh.php');
	if(isset($_POST["Import"])){

		$filename=$_FILES["file"]["tmp_name"];

		if($_FILES["file"]["size"] > 0){

			$file = fopen($filename, "r");

			$i = 0;

			while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE){

				if($i === 0){

				}else{

					$date = DateTime::createFromFormat('m/d/Y', $emapData[0]);

					$date = $date->format("Y-m-d");

					$sql = "INSERT INTO `holiday`(`date`, `occasion`, `city`) VALUES ('$date','$emapData[1]','$emapData[2]')";

					$result = $conn->prepare($sql);

					

					if(!$result->execute()){

						ob_end_flush();

						echo "<script type=\"text/javascript\">

						alert(\"Invalid File:Please Upload CSV File.\");

						window.location = \"./../holiday.php\"

						</script>";

    					exit();

					}

				}

				$i++;

				

			}

				ob_end_flush();

				echo "<script type=\"text/javascript\">alert(\"CSV File has been successfully Imported.\"); window.location = \"./../holiday.php\"</script>";

    			exit();

		}

		fclose($file);

		exit;

	}	 



}else{

	header('location: logout.php');

	exit;

}

?>		 