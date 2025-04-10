<?php
	session_start();
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'user'){
			header('Location: index.php');
			exit;
		}
    	require_once ('process/dbh.php');
        include("./hashing/Encrypt.php");
        include("./hashing/Decrypt.php");
    
        $i = 1;
        while($i < 100){
            $user_id = $i;
            $qry_attendance = "SELECT * FROM `employee_attendance` WHERE `emp_id` = '$user_id' AND `status` = 1 ORDER BY `date` ASC";
            $res_attendance = $conn->prepare($qry_attendance);
            $res_attendance->execute();
            while($row_att = $res_attendance->fetch()){
                $dec = new Decrypt($row_att["task"]);
                $information = json_decode($dec->getOutput(), true);
                $date_att = $row_att["date"];
                $qry_leave_chk = "SELECT * FROM `employee_leave` WHERE `id` = '$user_id' AND `status` = \"Approved\" AND '$date_att' BETWEEN `start` AND `end`";
                $res_leave_chk = $conn->prepare($qry_leave_chk);
                $res_leave_chk->execute();
                if($res_leave_chk->rowCount() > 0){
                    $leave_info = $res_leave_chk->fetch(PDO::FETCH_ASSOC);
                    $id_upd = $row_att["id"];
                    $information["task"] = $leave_info["reason"]." (System)";
                    $information["status"] = "3";
                    $enc = new Encrypt(json_encode($information));
                    $upd_info = $enc->getOutput();
                    $qry_upd_leave = "UPDATE `employee_attendance` SET `task` = ?, `status` = 3 WHERE `id` = ?";
                    $res_upd_leave = $conn->prepare($qry_upd_leave);
                    $res_upd_leave->execute([$upd_info, $id_upd]);
                }
            }
    
            $i++;
        }
        header("location: empattend.php");
        exit;
	}else{
		header('location: logout.php');
		exit;
	}

?>