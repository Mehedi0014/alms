<?php
    session_start();
    ob_start();
    require_once ('dbh.php');
    require_once(realpath(__DIR__)."/../hashing/Encrypt.php");
    require_once(realpath(__DIR__)."/../hashing/Decrypt.php");
    $id = $_SESSION['id'];
    $date = generateSanitize($_POST['date']);
    $start_time = generateSanitize($_POST['start_time']);
    $end_time = generateSanitize($_POST['end_time']);
    $current_date = date('Y-m-d');
    $task = generateSanitize($_POST['task']);
    $token = generateSanitize($_POST['token']);

    if($token !== $_SESSION['token']){
        unset($_SESSION['token']);
        ob_end_flush();
            echo "<script>
                alert('Invalid Arguement passed.');
                window.location.href='./../eloginwel.php';
            </script>";
            exit;

    }

    if(empty($task) && strlen($task) < 8){
        unset($_SESSION['token']);
        ob_end_flush();
            echo "<script>
                window.location.href='./../eloginwel.php';
            </script>";
            exit;

    }

    if($date === $current_date){
        $qry_atten_check = "SELECT `id`, `status` FROM `employee_attendance` WHERE `emp_id` = '$id' AND `date` = '$date'";
        $res_atten_check = $conn->prepare($qry_atten_check);
        $res_atten_check->execute();
        $row_atten_check_cnt = $res_atten_check->rowCount();
        if($row_atten_check_cnt > 0){
            $row_atten_check = $res_atten_check->fetch();
            $atten_id = $row_atten_check['id'];
            if($row_atten_check['status'] !== '1'){
                $output = [
                    "change_date"   => date('Y-m-d H:i:s'),
                    "task"          => $task,
                    "status"        => $atten_id,
                    "salt"          => date('Y-m-d H:i:s')
                ];
                $enc = new Encrypt(json_encode($output));
                $task = $enc->getOutput();
                $params = array(
                    'start_time' => $start_time ,
                    'end_time' => $end_time ,
                    'task' => $task ,
                    'atten_id' => $atten_id
                );

                $sql = "UPDATE `employee_attendance` SET `start_time`=:start_time,`end_time`=:end_time,`task`=:task WHERE `id` = :atten_id";
                $result = $conn->prepare($sql);
                $result->execute($params);
                unset($_SESSION['token']);
                ob_end_flush();
                echo "<script>
                alert('Attendance updated successfully.');
                window.location.href='./../eloginwel.php';
                </script>";
                exit;

            }else{

                unset($_SESSION['token']);
                ob_end_flush();
                echo "<script>
                alert('Attendance failed to update.');
                window.location.href='./../eloginwel.php';
                </script>";
                exit;
            }

        }else{
            $output = [
                "change_date"   => date('Y-m-d H:i:s'),
                "task"          => $task,
                "status"        => '0',
                "salt"          => date('Y-m-d H:i:s')
            ];

            $enc = new Encrypt(json_encode($output));
            $task = $enc->getOutput();
            $params = array(
                'start_time' => $start_time ,
                'end_time' => $end_time ,
                'task' => $task ,
                'emp_id' => $id ,
                'date' => $date 
            );

            $sql = "INSERT INTO `employee_attendance`(`emp_id`,`date`, `start_time`, `end_time`, `task`, `status`) VALUES (:emp_id,:date,:start_time,:end_time,:task,'0')";
            $result = $conn->prepare($sql);
            $result->execute($params);
            unset($_SESSION['token']);
            ob_end_flush();
            echo "<script>
                alert('Attendance insert successfully.');
                window.location.href='./../eloginwel.php';
            </script>";
            exit;
        }
    }else{
        $qry_atten_check = "SELECT `id`, `status` FROM `employee_attendance` WHERE `emp_id` = '$id' AND `date` = '$date'";
        $res_atten_check = $conn->prepare($qry_atten_check);
        $res_atten_check->execute();
        $row_atten_check_cnt = $res_atten_check->rowCount();
        if($row_atten_check_cnt > 0){
            $row_atten_check = $res_atten_check->fetch();
            $atten_id = $row_atten_check['id'];
            $output = [
                "change_date"   => date('Y-m-d H:i:s'),
                "task"          => $task,
                "status"        => $atten_id,
                "salt"          => date('Y-m-d H:i:s')
            ];
            $enc = new Encrypt(json_encode($output));
            $task = $enc->getOutput();
            $params = array(
                'start_time' => $start_time ,
                'end_time' => $end_time ,
                'task' => $task ,
                'status' => '6',
                'atten_id' => $atten_id
            );
            $sql = "UPDATE `employee_attendance` SET `start_time`=:start_time,`end_time`=:end_time,`task`=:task, `status`=:status WHERE `id` = :atten_id";
            $result = $conn->prepare($sql);
            $result->execute($params);
            unset($_SESSION['token']);
            ob_end_flush();
            echo "<script>
                alert('Pending task Update. Please Approve by Admin.');
                window.location.href='./../eloginwel.php';
            </script>";
            exit;
        }else{
            $output = [
                "change_date"   => date('Y-m-d H:i:s'),
                "task"          => $task,
                "status"        => '6',
                "salt"          => date('Y-m-d H:i:s')
            ];
            $enc = new Encrypt(json_encode($output));
            $task = $enc->getOutput();
            $params = array(
                'start_time' => $start_time ,
                'end_time' => $end_time ,
                'task' => $task ,
                'emp_id' => $id ,
                'date' => $date 
            );
            $sql = "INSERT INTO `employee_attendance`(`emp_id`,`date`, `start_time`, `end_time`, `task`, `status`) VALUES (:emp_id,:date,:start_time,:end_time,:task,'6')";
            $result = $conn->prepare($sql);
            $result->execute($params);
            unset($_SESSION['token']);
            ob_end_flush();
            echo "<script>
                alert('Can't Update Previous Task');
                window.location.href='./../eloginwel.php';
            </script>";
            exit;
        }
    }
ob_end_flush();
exit;
?>