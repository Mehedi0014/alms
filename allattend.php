<?php
  session_start();
  require_once ('process/dbh.php');
  include("./hashing/Decrypt.php");
  if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    if($_SESSION['role'] === 'user'){
      header('Location: index.php');
      exit;
    }
    $employee_id=$_GET['id'];
    $sqla = "SELECT * FROM `employee` where id = '$employee_id'";
		$resulta = $conn->query($sqla);
		$employeen = $resulta->fetch();
		$empName = ($employeen['firstName']);
    $sql = "SELECT * FROM `employee_attendance` WHERE `emp_id` = ? ORDER BY date DESC";
    $result = $conn->prepare($sql);
    $result->execute([$employee_id]);
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
                  <li><a href="empattend.php">Employee Attendance</a></li>
                  <li><a href="logout.php">Log Out</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
              </nav>
            </div>
          </header>
          <div style=" display: flex; justify-content: center;">
      			<div style="padding-top: 100px; width: 97%; justify-content: center;">
              <div class="container-fluid" style="height: 100%; min-height: 82.7vh;">	
                <div id="divimg" style="">
                  <h1 style="font-family: 'Montserrat'; text-align: center;color: #53486a; text-transform: capitalize; padding-top: 7%;"><i>"<?=$empName." ".$employeen['lastName']?>" All Attendance</i> </h1>
                  <table id="table_allattend" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                          <th>SL</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total time</th>
                        <th>Task</th>
                        <th>Status</th>
                      </tr>
                    </thead>  
                    <tbody>
                        <?php

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
                                              echo "<td>".$employee['date']."</td>";
                                              echo "<td>".$employee['start_time']."</td>";
                                              echo "<td>".$employee['end_time']."</td>";
                                              echo "<td>".($time->h).' hrs'." ".($time->i).' min'."</td>";
                                              if($task !== NULL){
                                                    if(empty($mod)){
                                                        echo "<td>".$task['task']."</td>";
                                                        // echo "<pre>";
                                                        // echo "No problem\n";
                                                        // var_dump($task);
                                                        // echo "</pre>";
                                                  }else{
                                                        echo "<td style='background: red; color: white;'>".$mod."</td>";
                                                        // echo "<pre>";
                                                        // echo "problem\n";
                                                        // var_dump($task);
                                                        // echo "</pre>";
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
                                  </tbody>       
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php

    include "./footer.php";
      ?>
      
    	<script>
        $(document).ready( function () {
            $('#table_allattend').DataTable( {
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

      } );
      		});
        </script>
      </html>
      <?php 
        }else{
        	  header('location: logout.php');
      	  exit;
        }
      ?>