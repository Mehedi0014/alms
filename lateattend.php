<?php 
	session_start();
	require_once ('process/dbh.php');

    include("./hashing/Encrypt.php");
	include("./hashing/Decrypt.php");
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'user'){
			header('Location: index.php');
			exit;
		}
	include "./header.php";

?>
        <header id="header" class="fixed-top">
            <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h1 class="logo"><a href="index.php"><img src="assets\logo\Disseminarebd_logo.png"></a></h1>
                <h1 class="logo"><a href="index.php"><span>AL</span>MS</a></h1>
                <h1 class="logo"><a href="index.php"><img src="assets\logo\Connecting_Dot_logo.png"></a></h1>
            </div>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                <li><a href="aloginwel.php">HOME</a></li>
                <li><a href="addemp.php">Add Employee</a></li>
                <li><a href="empattend.php">Employee Attendance</a></li>
                <li><a href="lateattend.php" class="active">Late Attendance</a></li>
                <li><a href="empleave.php">Employee Leave</a></li>
                <li><a href="holiday.php">Holiday</a></li>
                <li><a href="setting.php">Setting</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            </div>
        </header>
        <div style="padding-top: 100px; width: 100%;">
            <div class="container" style="height: 100%; min-height: 82.7v;">		
                <div id="divimg">
                    <h2 style="text-align: center;">Approve Late Attendance Submit</h2>
                    <table id="table_leteattend" class="display">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Total Time</th>
                            <th>Task</th>
                            <th>Option</th>
                        </tr>
                    <thead>	
                    <tbody>
                        <?php
                            $sql="SELECT a.`e_id`, a.`firstName`, a.`lastName`,b.`id`, b.`date`, b.`start_time`, b.`end_time`, b.`task`, b.`status` FROM `employee_attendance` AS b LEFT JOIN `employee` AS a ON b.`emp_id` = a.id WHERE ((b.`status`= '6') OR (b.`status`= '5' AND a.`status`='0')) ORDER BY b.`status` DESC, b.`date` DESC";
                            $result = $conn->prepare($sql);
                            $result->execute();
                            $i=0;
                            while ($employee = $result->fetch()){
                                $start_time = new DateTime($employee['start_time']);
                                $end_time = new DateTime($employee['end_time']);
                                $time = $start_time->diff($end_time);
                                $i++;
                                $dec = new Decrypt($employee['task']);
                                $task = json_decode($dec->getOutput(), true);
                                $update = new DateTime($task['change_date']);
                                $saved_date = new DateTime($employee['date']);
                                $sub_date = $saved_date->diff($update);
                                echo "<tr>";
                                    echo "<td>".$i."</td>";
                                    echo "<td>".$employee['e_id']."</td>";
                                    echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
                                    echo "<td>".$employee['date']."</td>";
                                    echo "<td>".($time->h).' hrs'." ".($time->i).' min'."</td>";
                                    echo "<td>".$task['task']."</td>";
                                    if($employee['status'] == '6'){
                                        echo "<td><a href=\"process/lateattendprocess.php?id=$employee[id]\"<button type='button' class='btn btn-secondary'>Approve</button></a></td>";
                                    }else{
                                        echo "<td> Approved </td>";
                                    }
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
		include "./footer.php";
        ?>
        <script>
            $(document).ready( function () {
                $('#table_leteattend').DataTable({
                    "info": true,
                    searching: true,
                    "lengthChange": true,
                    "scrollCollapse": true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'print', 'html5', {
                            text: 'Reload',
                            action: function ( e, dt, node, config ) {
                                location.reload();
                            }
                        }
                    ],
                    colReorder: true
                });
            });
        </script>

    </html>

<?php 
	}else{
		header('location: logout.php');
		exit;
	}

?>