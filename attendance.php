<?php 
	session_start();
	include("./hashing/Encrypt.php");
	include("./hashing/Decrypt.php");
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'admin'){
			header('Location: index.php');
			exit;
		}
		$id = (isset($_SESSION['id']) ? $_SESSION['id'] : '');
		require_once ('process/dbh.php');
		include "./header.php";  
		$sql = "SELECT * FROM `employee` where id = ? ";
		$result = $conn->prepare($sql);
		$result->execute([$id]);
		$employee = $result->fetch();

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
        					<li><a href="eloginwel.php">HOME</a></li>
        					<li><a href="myprofile.php">My Profile</a></li>
        					<li><a href="attendance.php" class="active">Attendance</a></li>
        					<li><a href="applyleave.php">Apply Leave</a></li>
        					<li><a href="logout.php">Log Out</a></li>
        				</ul>
        				<i class="bi bi-list mobile-nav-toggle"></i>
        			</nav>
        			</div>
        		</header>
        		<div class="divider"></div>
        		<div class="font-robo" style="margin: 150px 0 60px 0;">
        			<div class="wrapper wrapper--w680">
        				<div class="card card-1" style="border-radius: 20px;">
        					<div class="card-heading"></div>
        					<div class="card-body">
        						<h2 class="title">Attendance</h2>
        						<br>
        						<div class="alert alert-danger text-center" role="alert">

                                  <strong><i>Please...</i> share your Daily task before 10 PM. </strong>

                                </div>
        						<br>
        						<form action="process/applyattendanceprocess.php" method="POST" onSubmit="return validate(this);">
        						    <?php
        						        
        						    ?>
        							<div class="row row-space">
        								<div class="col-2">
        									<p>Date</p>
        									<div class="input-group">
        									       <input type="hidden" name='token' value="<?php 
                            						    $str = json_encode([
                            						            'salt'  => bin2hex(random_bytes(5)),
                            						            'id'    => $id,
                            						            'date'  => date("Y-m-d H:i:s"),
                            						            'salt2'  => bin2hex(random_bytes(5))
                            						        ]);
                            						    $enc = new Encrypt($str);
                            						    $output = $enc->getOutput();
                            						    $_SESSION['token'] = $output;
                            						    echo $output?>"
                            						/>
        										<input class="input--style-1" type="date"  value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>" placeholder="date" required="required" name="date">
        									</div>
        								</div>
        							</div>
        							<div class="row row-space">
        								<div class="col-2">
        									<p>Start time</p>
        									<div class="input-group">
        										<input class="input--style-1" type="time" placeholder="start time" required="required" name="start_time">
        									</div>
        								</div>
        								<div class="col-2">
        									<p>End time</p>
        									<div class="input-group">
        										<input class="input--style-1" type="time" placeholder="end time" required="required" name="end_time">
        									</div>
        								</div>
        							</div>
        							<div class="row row-space">
        								<div class="form-group">
        									<p>Task</p>
        									<textarea id="w3review" type="textarea" required="required" name="task" rows="4" cols="50" class="form-control"></textarea>
        								</div>
        							</div>
        							<div class="p-t-20">
        								<button class="btn btn--radius btn--green" type="submit">Submit</button>
        							</div>
        						</form>
        					</div>
        				</div>
        			</div>
        		</div>
        		<div class="container-fluid" style="margin-bottom: 40px">
        			<br>
        			<div class="table-responsive">
            			<table id="table_attend" class="display">
            				<thead>
            					<tr>
            						<th>SL</th>
            						<th>Name</th>
            						<th>Date</th>
            						<th>Start time</th>
            						<th>End time</th>
            						<th>Total time</th>
            						<th>Task</th>
            						<th>Status</th>
            					</tr>
            				<thead>	
            				<tbody>
            					<?php
            						$sql = "SELECT 
            							a.`firstName`,
            							a.`lastName`,
            							b.`date`,
            							b.`start_time`,
            							b.`end_time`,
            							b.`task`,
            							b.`status`
            						FROM `employee_attendance` AS b 
            						LEFT JOIN `employee` AS a 
            						ON b.`emp_id` = a.id
            						WHERE a.`id` = ? ORDER BY date DESC LIMIT 30";
            						$result = $conn->prepare($sql);
            						$result->execute([$id]);
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
            				</tbody>
            			</table>
        			    
        			</div>
        		</div>
        	<?php
        		include "./footer.php";
        	?>
        	<script>
        		$(document).ready( function () {
        			$('#table_attend').DataTable({
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
        		
        		function validate(params){
        		    var st_time = $("input[type=time][name=start_time]").val()
        		    var end_time = $("input[type=time][name=end_time]").val()
        		    if(st_time <= end_time){
        		        return true;
        		    }else{
        		        alert("End time should be more than Start time. Please review your task time properly.")
        		    }
        		    return false;
        		}
        	</script>
        </html>
        <?php 
	}else{
		header('location: logout.php');
		exit;
	}
?>