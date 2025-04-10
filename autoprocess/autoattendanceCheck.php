<?php
    include __DIR__."/../process/dbh.php";
    include __DIR__."/../hashing/Decrypt.php";
    include __DIR__."/../hashing/Encrypt.php";
    $sapi_name = substr(trim(php_sapi_name()), 0, 3);
    
    if($sapi_name === 'cli'){
        $time = date("H");
        if($time === '23'){
            $sql = "SELECT a.`id`, a.`e_id`, a.`city`, a.`firstName`, b.`id` AS `attendance_id`,  b.`emp_id`,  b.`date`, b.`status` FROM `employee` AS a LEFT JOIN `employee_attendance` As b ON a.`id` = b.`emp_id` AND b.`date` = CURRENT_DATE OR b.`date` IS NULL WHERE a.`status` = '0'";
            $res_chk = $conn->query($sql);
            $row_chk = $res_chk->fetchAll();
            foreach($row_chk as $k=>$v){
                if($v['status'] !== '0' || $v['status'] !== '5'|| $v['status'] !== '6'){
                    if($v['status'] === NULL){
                        if(($v['city'] === "bd" && ((date('D') === "Fri") || date('D') === "Sat")) || ($v['city'] === "ind" && ((date('D') === "Sun") || date('D') === "Sat"))){
                            $output = [
                                    "change_date"   => date('Y-m-d H:i:s'),
                                "task"          => "Weekend (system)",
                                "status"        => '2',
                                "salt"          => date('Y-m-d H:i:s')
                                ];
                            $enc = new Encrypt(json_encode($output));
                            $task = $enc->getOutput();
                            $qry_insert = "INSERT INTO `employee_attendance`(`emp_id`, `date`, `start_time`, `end_time`, `task`, `status`) VALUES (?, CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, ?, '2')";
                            $res_insert = $conn->prepare($qry_insert);
                            $res_insert->execute([$v['id'], $task]);
                        }else{
                            $qry_holiday = "SELECT `occasion` FROM `holiday` WHERE `date` = CURRENT_DATE AND `city` = ?";
                            $res_holiday = $conn->prepare($qry_holiday);
                            $res_holiday->execute([$v['city']]);
                            if($res_holiday->rowCount() === 1){
                                $row_holiday = $res_holiday->fetch();
                                $output = [
                                        "change_date"   => date('Y-m-d H:i:s'),
                                    "task"          => $row_holiday['occasion']."  (system)",
                                    "status"        => '4',
                                    "salt"          => date('Y-m-d H:i:s')
                                    ];
                                $enc = new Encrypt(json_encode($output));
                                $task = $enc->getOutput();
                                $qry_insert = "INSERT INTO `employee_attendance`(`emp_id`, `date`, `start_time`, `end_time`, `task`, `status`) VALUES (?, CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, ?, '4')";
                                $res_insert = $conn->prepare($qry_insert);
                                $res_insert->execute([$v['id'], $task]);
                            }else{
                                $id = $v['id'];
                                $qry_leave = "SELECT `reason`, `type` FROM `employee_leave` WHERE `id` = ? AND `start`<= CURRENT_DATE AND `end` >= CURRENT_DATE AND `status` = 'Approved'";
                                $res_leave = $conn->prepare($qry_leave);
                                $res_leave->execute([$id]);
                                if($res_leave->rowCount() === 1){
                                    $row_leave = $res_leave->fetch();
                                    $output = [
                                            "change_date"   => date('Y-m-d H:i:s'),
                                        "task"          => $row_leave['reason']." (". $row_leave['type'] . ")  (system)",
                                        "status"        => '3',
                                        "salt"          => date('Y-m-d H:i:s')
                                        ];
                                    $enc = new Encrypt(json_encode($output));
                                    $task = $enc->getOutput();
                                    $qry_insert = "INSERT INTO `employee_attendance`(`emp_id`, `date`, `start_time`, `end_time`, `task`, `status`) VALUES (?, CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, ?, '3')";
                                    $res_insert = $conn->prepare($qry_insert);
                                    $res_insert->execute([$id, $task]);
                                }else{
                                    $output = [
                                            "change_date"   => date('Y-m-d H:i:s'),
                                        "task"          => 'Absent  (system)',
                                        "status"        => '1',
                                        "salt"          => date('Y-m-d H:i:s')
                                        ];
                                    $enc = new Encrypt(json_encode($output));
                                    $task = $enc->getOutput();
                                    $qry_insert = "INSERT INTO `employee_attendance`(`emp_id`, `date`, `start_time`, `end_time`, `task`, `status`) VALUES (?, CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, ?, '1')";
                                    $res_insert = $conn->prepare($qry_insert);
                                    $res_insert->execute([$id, $task]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

?>