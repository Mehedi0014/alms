<?php 
	session_start();
	include("./hashing/Decrypt.php");
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'admin'){
			header('Location: index.php');
			exit;
		}
		$id = (isset($_SESSION['id']) ? $_SESSION['id'] : '');
		require_once ('process/dbh.php');
		$sql = "SELECT * FROM `employee` where id = '$id'";
		$result = $conn->query($sql);
		$employeen = $result->fetch();
		$empName = ($employeen['firstName']);
		$empregn = ($employeen['city']);
		include "./header.php";
?>

<style>

@media only screen and (max-width: 600px) {
    .ssss {
      margin-top: 60px;
    }
    
    .atten_status{
        margin-bottom: 0;
    }
  }
</style>

	<header id="header" class="fixed-top">
		<div class="container d-flex justify-content-between align-items-center">
			<div class="d-flex align-items-center">
				<h1 class="logo"><a href="index.php"><img src="assets\logo\Disseminarebd_logo.png"></a></h1>
				<h1 class="logo"><a href="index.php"><span>AL</span>MS</a></h1>
				<h1 class="logo"><a href="index.php"><img src="assets\logo\Connecting_Dot_logo.png"></a></h1>
			</div>
			<nav id="navbar" class="navbar order-last order-lg-0">
				<ul>
					<li><a href="eloginwel.php" class="active">HOME</a></li>
					<li><a href="myprofile.php">My Profile</a></li>
					<li><a href="attendance.php">Attendance</a></li>
					<li><a href="applyleave.php">Apply Leave</a></li>
					<li><a href="logout.php">Log Out</a></li>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header>
	<div class="container-fluid ssss g-0">
		<div class="table-responsive">
			<div id="divimg">
				<div>
					<h1 style="font-family: 'Montserrat'; text-align: center;color: #53486a; text-transform: capitalize; padding-top: 7%;">Welcome <i style="color: #0055a6;font-weight: 700;">"<?=$empName." ".$employeen['lastName']?>"</i> </h1>
					<?php 
						if(isset($_SESSION['success_msg'])){
							?>
							<div class="success_msg">
								<h5><?=$_SESSION['success_msg']?></h5>
							</div>
							<?php
							unset($_SESSION['success_msg']);
						}elseif(isset($_SESSION['error_msg'])){
							?>
							<div class="error_msg">
								<h5><?=$_SESSION['error_msg']?></h5>
							</div>
							<?php
							unset($_SESSION['error_msg']);
						}
					?>
					<div class="container">
						<div class="row">
							<div class="col-md-6 perform_status" style="margin-top: 20px;">
								<h2 style="font-family: 'Montserrat', sans-serif; font-size: 25px; text-align: center;">Performance</h2>
								<div class="container">
									<div class="d-flex justify-content-center">
										<div style="width: 100%">
											<canvas id="myChart" ></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6" style="margin-top: 20px;" height>
								<h2 style="font-family: 'Montserrat', sans-serif; font-size: 25px; text-align: center;">Upcoming Holiday</h2>
								<table id="table-2" class="display">
									<thead>
										<tr>
											<th>Sl.</th>
											<th>Date</th>
											<th>Occasion</th>
											<th>Upcoming</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$sal = "SELECT `date`,`occasion`, DATEDIFF(`date`, CURRENT_DATE) AS `remain_date` FROM `holiday` WHERE LOWER(`city`) = ? AND `date` >= CURRENT_DATE ORDER BY `date` ASC LIMIT 0,3";
											$resultsal = $conn->prepare($sal);
											$resultsal->execute([$empregn]);
											$j = 0;
											while ($holiday = $resultsal->fetch()) {
												$j++;
												echo "<tr>";
													echo "<td>".$j."</td>";
													echo "<td>".$holiday['date']."</td>";
													echo "<td>".$holiday['occasion']."</td>";
													if(($holiday['remain_date']) === "0"){
														echo "<td>Today</td>";
													}else if(($holiday['remain_date']) === "1"){
														echo "<td>Tomorrow</td>";
													}else{
														echo "<td>After ".($holiday['remain_date'])." Days</td>";
													}

												echo "</tr>";
											}
										?>
									</tbody>
								</table>
							</div>
							<div class="col-md-6 atten_status" style="margin: 20px 0 20px 0;">
								<h2 style="font-family: 'Montserrat', sans-serif; font-size: 25px; text-align: center;">Attendance Satus</h2>
								<table id="table-3" class="display">
									<thead>
										<tr>
										    <th>SL.</th>
											<th>Date</th>
											<th>Total time</th>
											<th>Task</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$res = "SELECT * FROM `employee_attendance` WHERE `emp_id` = ? ORDER BY date DESC LIMIT 0,2";
										$resultr = $conn->prepare($res);
										$resultr->execute([$id]);
										$j=0;
										while ($employee = $resultr->fetch()) {
											$start_time = new DateTime($employee['start_time']);
											$end_time = new DateTime($employee['end_time']);
											$time = $start_time->diff($end_time);
											$j++;
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
											    echo "<td>".$j."</td>";
												echo "<td>".$employee['date']."</td>";
												echo "<td>".($time->h).' hrs'." ".($time->i).' min'."</td>";
												if($task !== NULL){
												    if(empty($mod)){
                								        echo "<td>".$task['task']."</td>";
												    }else{
                								        echo "<td style='background: red; color: white;'>".$mod."</td>";
												    }
                							    }else{
                								    echo "<td>Try to modify the task.</td>";
                							    }
											echo "</tr>";
										}
									?>
									</tbody>
								</table>
							</div>
							<div class="col-md-6 leave_status" style="margin: 20px 0 20px 0;">
								<h2 style="font-family: 'Montserrat', sans-serif; font-size: 25px; text-align: center;">Leave Satus</h2>
								<table id="table-4" class="display">
									<thead>
										<tr>
										    <th>SL.</th>
											<th>Type</th>
											<th>Total Days</th>
											<th>Reason</th>
											<th>Status</th>
											<th>Comments</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$res = "SELECT * FROM employee_leave Where id = ? order by token DESC LIMIT 0,2";
										$resultr = $conn->prepare($res);
										$resultr->execute([$id]);
										$j=0;
										while ($employee = $resultr->fetch()) {
											$date1 = new DateTime($employee['start']);
											$date2 = new DateTime($employee['end']);
											$interval = $date1->diff($date2);
											$j++;
											echo "<tr>";
											    echo "<td>".$j."</td>";
												echo "<td>".$employee['type']."</td>";
												echo "<td>".(($interval->days)+1)."</td>";
												echo "<td>".$employee['reason']."</td>";
												echo "<td>".$employee['status']."</td>";
												echo "<td>".$employee['cmnt']."</td>";
											echo "</tr>";
										}
									?>
									</tbody>
								</table>
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
	<script src="assets/js/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		

		var	dataset = JSON.stringify({"label": "get_chart_data_perform"});
		var request = $.ajax({
			url: "response.php",
			contentType: "application/json",
			method: "POST",
			data: dataset,
			dataType: "JSON"
		})

		request.done(function(response){
		    var dat;
		    var label_arr = [];
		    var res_data = response.data;
		    for(dat in res_data){
		       var json =  {
                  label: dat,
                  data: res_data[dat].hours,
                  borderColor: res_data[dat].color,
                  fill: false,
                  cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }
		        label_arr.push(json)
		    }
			const data = {labels: response.date,
			datasets: label_arr
			};
			const config = {
				type: 'line',
				data: data,
				options: {
				    responsive: true,
                    plugins: {
                      title: {
                        display: false,
                        text: ''
                      },
                    },
                    interaction: {
                      intersect: false,
                    },
                    scales: {
                      x: {
                        display: true,
                        title: {
                          display: false,
                          text: 'dates'
                        }
                      },
                      y: {
                        display: true,
                        title: {
                          display: true,
                          text: 'Hours'
                        },
                        suggestedMin: 0,
                        suggestedMax: 15
                      }
                    }
				}
			};
			
			
			const myChart = new Chart(document.getElementById('myChart'), config);
		});

		request.fail(function(txtCode, message){
			console.error(txtCode)
			console.error(message)
		});
	</script>
	<script>
		$(document).ready( function () {
			
			$('#table-2').DataTable({
				"info": false,
				paging: false,
				searching: false,
				"lengthChange": false,
				"scrollY": "150px",
				"scrollCollapse": false
			});
			$('#table-3').DataTable({
				"info": false,
				paging: false,
				searching: false,
				"lengthChange": false,
				"scrollY": "200px",
				"scrollCollapse": false
			});
			$('#table-4').DataTable({
				"info": false,
				paging: false,
				searching: false,
				"lengthChange": false,
				"scrollY": "200px",
				"scrollCollapse": false
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