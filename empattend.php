<?php
  session_start();
  if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    if($_SESSION['role'] === 'user'){
      header('Location: index.php');
      exit;
    }
    require_once ('process/dbh.php');
    include("./hashing/Decrypt.php");
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
          <li><a href="addemp.php" >Add Employee</a></li>
          <li><a href="empattend.php" class="active">Employee Attendance</a></li>
          <li><a href="lateattend.php">Late Attendance</a></li>
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
    <div class="container-fluid" style="height: 100%; min-height: 82.7vh;">	

			<div style="text-align: center;">

				<div class="d-flex justify-content-center">

					<button class="btn btn-primary ml-2" id="btn_1" style="margin-right: 10px;" data-target="#details_1" onclick="return tabopen(this);">Employee WorkSheet</button>

					<button class="btn btn-info" id="btn_2" style="margin-right: 10px;" data-target="#details_2" onclick="return tabopen(this);">Employee Attendance</button>
					<?php
					    if($_SESSION["role"] === "admin" && $_SESSION["id"] === "4"){
					        ?>
					            <a class="btn btn-danger" id="btn_2" href="attendace_check_fix.php" onclick="return confirm('Are you sure for adjusting the attendance?')" >Attendance Adjust</a>
					        <?php
					    }
					?>

				</div>

				<div class="containerfluid"  style="margin-top: 10px;">

					<div class="tab_view card collapse" id="details_1">
            <div id="divimg" style="">
              <br>
              <div class="card-head">
                Employee WorkSheet
              </div>
                <br>
              <table id="table_allattend" class="display" style="width: 100%;">
                <thead>
                  <tr>
                    <th>SL.</th>
                    <th>Employee Id</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                    <th>Task</th>
                    <th>Status</th>
                  </tr>
                <thead>  
                <tbody>
                  <?php
                    $sql = "SELECT 
                      a.`e_id`,
                      a.`firstName`,
                      a.`lastName`,
                      b.`date`,
                      b.`start_time`,
                      b.`end_time`,
                      b.`task`,
                      b.`status`
                  FROM `employee_attendance` AS b 
                  LEFT JOIN `employee` AS a 
                  ON b.`emp_id` = a.`id` ORDER BY date DESC";
                    $result = $conn->prepare($sql);
                    $result->execute();
                    $i = 0;
                    while ($employee = $result->fetch()) {
                      $start_time = new DateTime($employee['start_time']);
                      $end_time = new DateTime($employee['end_time']);
                      $time = $start_time->diff($end_time);  
                      $i++;
                      $dec = new Decrypt($employee['task']);
    					        $task = json_decode($dec->getOutput(), true);
    					        $update = new DateTime($task['change_date']);
    					        $saved_date = new DateTime($employee['date']);
    					        $sub_date = $saved_date->diff($update);
    					        $mod = "Modified";
    					        if($sub_date->d === 0){
    					          $mod = "";
    					        }else{
    					          if($employee['status'] === "5"){
                          $mod = "";
                      }else if($employee['status'] === "6"){
                          $mod = "<strong><i>Please Approve Your Attendance By Admin.</i></strong>";
                      }else{
                          $mod = "(Copied from )". $update->format("Y-m-d")." date status. <br><br><strong><i>Please don't try to modify the Database. Use ALMS system.</i></strong>";
                      }
    				          }
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$employee['e_id']."</td>";
                echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
                echo "<td>".$employee['date']."</td>";
                echo "<td>".$employee['start_time']."</td>";
                echo "<td>".$employee['end_time']."</td>";
                echo "<td>".($time->h).' hrs'." ".($time->i).' min'."</td>";
                if($task !== NULL){
                  if(empty($mod)){
                      echo "<td>".$task['task']."</td>";
                  }else{
                      echo "<td style='background: red; color: white;border: 1px solid #fff;'>".$mod."</td>";
                  }
                  }else{
                    echo "<td>Try to modify the task.</td>";
                  }
                        $qry_att = "SELECT `id`, `status_name` FROM `attendance_status` WHERE `id` = ?";
                        $res_att = $conn->prepare($qry_att);
                        $res_att->execute([$employee['status']]);
                        $row_att = $res_att->fetch();
                        echo "<td>".$row_att['status_name']."</td>";
                        echo "</tr>";
                  } 
                  ?>
                <tbody>       
              </table>
            </div>
          </div>

				</div>
        <div class="tab_view card" id="details_2">
          <div class="card-body">
            <div class="card-head">
              Show Attendance By Employee Name
            </div>
            <div class="card-body">
              <div class="container" style="height: 100%;">
                <div id="divimg" style="">
                  <table id="table_emp" class="display">
                    <thead>
                      <tr>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>View</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql = "SELECT `id`, `e_id`, `firstName`, `lastName` FROM `employee` ORDER BY `e_id` ASC ";
                        $result = $conn->prepare($sql);
                        $result->execute();
                        while ($employee = $result->fetch()) {
                          echo "<tr>";
                            echo "<td>".$employee['e_id']."</td>";
                            echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
                            echo "<td><a href=\"attend.php?id=".$employee['id']."\"><button type='button' class='btn btn-secondary'>Attendance</button></a></td>";
                            echo "<td><a href=\"allattend.php?id=".$employee['id']."\"><button type='button' class='btn btn-secondary'>All Attendance</button></a></td>";
                          echo "</tr>";
                          } 
                      ?>
                    <tbody>      
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

		</div>

	</div>
  <?php
    include "./footer.php";
  ?>

	<script>

		function tabopen(prop){

			var clicked_btn = $(prop).data("target");

			$(".tab_view").removeClass('show');

			$(".tab_view").addClass('collapse');

			$(clicked_btn).addClass("show");

		}
    $(document).ready( function () {
      $('#table_emp').DataTable({

				"info": true,

				searching: true,

				"lengthChange": true,

				"scrollY": "680px",

				"scrollCollapse": true

			});
      $('#table_allattend').DataTable( {
          dom: 'Bfrtip',
          "pageLength": 25,
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