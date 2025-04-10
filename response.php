<?php
    session_start();
	include("./hashing/Decrypt.php");
    require_once ('process/dbh.php');
    if(empty($_POST)){
        $data = json_decode(file_get_contents("php://input"), true);
    }else{
        $data = $_POST;
    }
    $label = htmlentities(filter_var($data['label'], FILTER_SANITIZE_STRING));
    if($label === "get_chart_data_perform"){
        if(isset($_POST['id'])){
            $e_id = $_POST['id'];
        }else{
            $e_id = $_SESSION['id'];
        }
        
        // $qry = "
        // SELECT
        //         IF(COALESCE(((((SUM(TIME_TO_SEC(TIMEDIFF(a.`end_time`, a.`start_time`)))-(COUNT(*) * 32400)) / 3600)*60)/100), 0) < 0, 0, COALESCE(((((SUM(TIME_TO_SEC(TIMEDIFF(a.`end_time`, a.`start_time`)))-(COUNT(*) * 32400)) / 3600)*60)/100), 0)) AS `extra`,
        //         COALESCE(((((COUNT(*) * 32400) / 3600)*40)/100), 0) AS `Perform`
        //     FROM `employee_attendance` a 
        // WHERE a.`emp_id` = ? AND a.`status` IN ('0', '1', '3', '5') AND DATE_FORMAT(a.`date`, '%Y%m') = DATE_FORMAT(CURRENT_DATE, '%Y%m')";
        
        // $qry = "SELECT 
        //             IF(COALESCE((SUM(TIME_TO_SEC(TIMEDIFF(`end_time`,`start_time`)))/3600)/b.c, 0) >9, 9,COALESCE((SUM(TIME_TO_SEC(TIMEDIFF(`end_time`,`start_time`)))/3600)/b.c, 0)) as `Perform`,
        //             IF(COALESCE(SUM(time_to_sec(TIMEDIFF(`end_time`,`start_time`))-(3600*9))/60, 0) < 0, 0, COALESCE(SUM(time_to_sec(TIMEDIFF(`end_time`,`start_time`))-(3600*9))/60, 0)) as `extra` 
        //         FROM `employee_attendance` JOIN (SELECT COUNT(`id`) as c FROM `employee_attendance` 
        //         WHERE `status` IN (0,1,3,5) AND `emp_id`= ? AND DATE_FORMAT(`date`, '%Y%m') = DATE_FORMAT(CURRENT_DATE, '%Y%m')) b 
        //         WHERE `status`=0 AND `emp_id` = ? AND DATE_FORMAT(`date`, '%Y%m') = DATE_FORMAT(CURRENT_DATE, '%Y%m')";

        // $res = $conn->prepare($qry);
        // $res->execute([$e_id, $e_id]);
        // $row = $res->fetch();
        // $data_pass = [(float) $row['Perform'],(float) $row['extra']];
        $qry_all = "SELECT `id`, CONCAT(`firstName`, ' ', `lastName`) AS `name`  FROM `employee` WHERE `status` = 0 ORDER BY `firstName` DESC;";
        $res_all = $conn->prepare($qry_all);
        $res_all->execute();
        
        $qry_all_date = "SELECT * FROM (SELECT a.`date`, IF(a.`status` = '6', CONCAT(DATE_FORMAT(a.`date`, '%W'), '(Wait for Approval)'), DATE_FORMAT(a.`date`, '%W')) AS `weekday`  FROM `employee_attendance` a WHERE a.`status` NOT IN (2,3,4) GROUP BY a.`date` ORDER BY a.`date` DESC LIMIT 15) aa ORDER BY aa.`date` ASC";
        $res_all_date = $conn->prepare($qry_all_date);
        $res_all_date->execute();
        $user = []; 
        $date = array();
        $weekday = array();
        while($row_all_date = $res_all_date->fetch()){
            array_push($date, $row_all_date['date']);
            array_push($weekday, $row_all_date['weekday']);
        }
        while($row_all = $res_all->fetch()){
            $name = $row_all['name'];
            $e_id = $row_all['id'];
            $hours = [];
            foreach($date AS $k => $v){
                $qry = "SELECT 
                    	IF(a.`status` = '6', CONCAT(a.`date`, ' (Wait for Approval)'), a.`date`) AS `date`,
                        IF(a.`status` = '6', CONCAT(DATE_FORMAT(a.`date`, '%W'), '(Wait for Approval)'), DATE_FORMAT(a.`date`, '%W')) AS `weekday`, 
                        IF(a.`status` = '6', NULL, DATE_FORMAT(TIMEDIFF(a.`end_time`, a.`start_time`), '%H.%i')) AS `hour`, 
                        a.`status`
                        FROM `employee_attendance` a
                        WHERE a.`emp_id` = ? AND a.`status` NOT IN (2, 3, 4) AND `date` = ?";
    
                $res = $conn->prepare($qry);
                $res->execute([$e_id, $v]);
                $row = $res->fetch();
                if($res->rowCount() > 0){
                    array_push($hours, (float)$row['hour']);
                }else{
                    array_push($hours, NULL);
                }
            }
            $user[$name]['date'] = $date;
            $user[$name]['hours'] = $hours;
            $user[$name]['weekday'] = $weekday;
            $user[$name]["color"] = "rgba(".random_int(0,255).", ".random_int(0,255).", ".random_int(0,255).", 1)";
        }
        
        
        
        
        $data_pass = [(float) $row['Perform'],(float) $row['extra']];
        header("Content-type: application/json");
        echo json_encode(array(
            'error' => false,
            'data'  => $user,
            'date'  => $date,
            'message'   => 'Data fetched Successfully'
        ));
    }

?>