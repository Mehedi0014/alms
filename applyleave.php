<?php 
	session_start();
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'admin'){
			header('Location: index.php');
			exit;
		}
		$id = (isset($_SESSION['id']) ? $_SESSION['id'] : '');
		require_once ('process/dbh.php');
		$sql = "SELECT * FROM `employee` where id = ?";
		$result = $conn->prepare($sql);
		$result->execute([$id]);
	include "./header.php";
?>	
		<style>
			.counter {
					color: #fff;
					background: linear-gradient(to right bottom, #FFD81B, #f9b12a);
					font-family: 'Dosis', sans-serif;
					text-align: center;
					width: 100%;
					height: 113px;
					padding: 12px 0px 25px;
					margin: 0px 5px;
					border-radius: 10px 10px 100px 100px;
					box-shadow: 0 0 15px -5px rgb(0 0 0 / 30%);
					overflow: hidden;
					position: relative;
					z-index: 1;
				}
			.counter .span {
					padding: 0;
					margin: 0;
					font-size: 30px;
				}
			.counter:after{
				content: '';
				background-color: #f9b12a;
				height: 100%;
				width: 100%;
				position: absolute;
				left: 0;
				top: 0;
				z-index: -1;
				clip-path: polygon(100% 0, 0% 100%, 100% 100%);
			}
			.counter .counter-value{
				font-size: 55px;
				font-weight: 600;
				line-height: 40px;
				margin: 0 0 15px;
				display: block;
			}
			.counter h3{
				font-size: 18px;
				font-weight: 600;
				text-transform: uppercase;
				letter-spacing: 1px;
				margin: 0 0 20px;
			}
			.counter.green{ background: linear-gradient(to right bottom, #a9dd23, #52C242); }
			.counter.green:after{ background: #52C242; }
			.counter.cgreen{ background: linear-gradient(to right bottom, #01AD9F, #008888); }
			.counter.cgreen:after{ background: #008888; }
			.counter.blue{ background: linear-gradient(to right bottom, #00C5EF, #0092f4); }
			.counter.blue:after{ background: #0092f4; }
			@media screen and (max-width:990px){
				.counter{ margin-bottom: 40px; }
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
					<li><a href="eloginwel.php">HOME</a></li>
					<li><a href="myprofile.php">My Profile</a></li>
					<li><a href="attendance.php">Attendance</a></li>
					<li><a href="applyleave.php" class="active">Apply Leave</a></li>
					<li><a href="logout.php">Log Out</a></li>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
		</header>
		<div style="padding-top: 100px; width: 100%;">
			<div style="width: 100%; max-width: 30rem; margin: 0 auto;">
				<div class="container">
					<div class="d-grid">
						<a href="leavepolicy.php" class="btn btn-info">Leave Policy</a>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12" style="display: flex;flex-gap">
							<div class="counter">
								<?php
								$sql="SELECT `start`,`end` FROM `employee_leave` WHERE `id`= ? AND YEAR(`start`) = YEAR(CURDATE()) AND `status`=? AND `type`=? ";
								$result = $conn->prepare($sql);
								$total = $result->execute([$id,'Approved','Sick']);						
								$count = 0;
								while ($employee_leave = $result->fetch()){
									$date1 = date_create($employee_leave['start']);
									$date2 = date_create($employee_leave['end']);
									$diff =  date_diff($date1 , $date2);
									$val = ($diff->format("%a")+1);
									$count += $val;
								}
								?>
								<div style="font-size: 28px;">
									<span><?php echo(8-$count); ?></span>
								</div>
								<h3>Sick Leave</h3>
							</div>
							<div class="counter">
								<?php
								$sql="SELECT `start`,`end` FROM `employee_leave` WHERE `id`= ? AND YEAR(`start`) = YEAR(CURDATE()) AND `status`=? AND `type`=?";
								$result = $conn->prepare($sql);
								$total = $result->execute([$id,'Approved','Halfday']);						
								$count = 0;
								while ($employee_leave = $result->fetch()){
									$date1 = date_create($employee_leave['start']);
									$date2 = date_create($employee_leave['end']);
									$diff =  date_diff($date1 , $date2);
									$val = ($diff->format("%a")+1);
									$count += $val;
								}
								?>
								<div style="font-size: 28px;">
									<span><?php echo ($count*.5);?></span>
								</div>
								<h3>Halfday Leave</h3>
							</div>
							<div class="counter">
								<?php
								$sql="SELECT `start`,`end` FROM `employee_leave` WHERE `id`= ? AND YEAR(`start`) = YEAR(CURDATE()) AND `status`=? AND `type`=? ";
								$result = $conn->prepare($sql);
								$total = $result->execute([$id,'Approved','Casual']);						
								$count = 0;
								while ($employee_leave = $result->fetch()){
									$date1 = date_create($employee_leave['start']);
									$date2 = date_create($employee_leave['end']);
									$diff =  date_diff($date1 , $date2);
									$val = ($diff->format("%a")+1);
									$count += $val;
								}
								?>
								<div style="font-size: 28px;">
									<span><?php echo(8-$count);?></span>
								</div>
								<h3>Casual Leave</h3>
							</div>
							<div class="counter">
								<?php
								$sql="SELECT `start`,`end` FROM `employee_leave` WHERE `id`= ? AND YEAR(`start`) = YEAR(CURDATE()) AND `status`=? AND `type`=?";
								$result = $conn->prepare($sql);
								$total = $result->execute([$id,'Approved','Special']);						
								$count = 0;
								while ($employee_leave = $result->fetch()){
									$date1 = date_create($employee_leave['start']);
									$date2 = date_create($employee_leave['end']);
									$diff =  date_diff($date1 , $date2);
									$val = ($diff->format("%a")+1);
									$count += $val;
								}
								?>
								<div style="font-size: 28px;">
									<span><?php echo (1-$count);?></span>
								</div>
								<h3>Special Leave</h3>
							</div>
							<div class="counter">
								<?php
								$sql="SELECT `start`,`end` FROM `employee_leave` WHERE `id`= ? AND YEAR(`start`) = YEAR(CURDATE()) AND `status`=? AND `type`=?";
								$result = $conn->prepare($sql);
								$total = $result->execute([$id,'Approved','Earned']);						
								$count = 0;
								while ($employee_leave = $result->fetch()){
									$date1 = date_create($employee_leave['start']);
									$date2 = date_create($employee_leave['end']);
									$diff =  date_diff($date1 , $date2);
									$val = ($diff->format("%a")+1);
									$count += $val;
								}
								?>
								<div style="font-size: 28px;">
									<span><?php echo(10-$count);?></span>
								</div>
								<h3>Earned Leave</h3>
							</div>
						</div>
        			</div>
				</div>	
			</div>
    	</div>
		<div class=" font-robo" style="margin:40px 0;">
			<div class="wrapper wrapper--w680">
				<div class="card card-1" style="border-radius:20px;">
					<div class="card-heading"></div>
					<div class="card-body">
						<h2 class="title">Apply Leave Form</h2>
						<form action="process/applyleaveprocess.php" method="POST">
							<input type="hidden" name="user_id" value="<?php echo $id;?>">
								<div class="form-group">
									<select class="form-control" name="type" required="required" id="">
										<option value="">-- Select Leave option --</option>
										<option value="Sick">Sick Leave</option>
										<option value="Halfday">Halfday Leave</option>
										<option value="Public">Public Leave</option>
										<option value="Casual">Casual Leave</option>
										<option value="Special">Special Leave</option>
										<option value="Earned">Earned Leave</option>
									</select>
                                </div>
								<div class="input-group">
									<input class="input--style-1" type="text" placeholder="Reason" required="required" name="reason">
								</div>
								<div class="row row-space">
									<div class="col-2">
									<p>Start Date</p>
									<div class="input-group">
										<input class="input--style-1" type="date" placeholder="start" required="required" name="start">
									</div>
								</div>
								<div class="col-2">
									<p>End Date</p>
									<div class="input-group">
										<input class="input--style-1" type="date" placeholder="end" required="required" name="end">
									</div>
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
				<table id="table_leave" class="display">
					<thead>
						<tr>
							<th>Employee ID</th>
							<th>Name</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Total Days</th>
							<th>Reason</th>
							<th>Status</th>
							<th>Type</th>
							<th>Comments</th>
						</tr>
					</thead>	
					<tbody>
						<?php
							$sql = "SELECT employee.id, employee.e_id, employee.firstName, employee.lastName, employee_leave.start, employee_leave.end, employee_leave.reason, employee_leave.status,employee_leave.type, employee_leave.cmnt From employee, employee_leave Where employee.id = $id and employee_leave.id = $id AND YEAR(employee_leave.`start`) = YEAR(CURDATE()) order by employee_leave.token";
							$result = $conn->prepare($sql);
							$result->execute();
							while ($employee = $result->fetch()) {
								$date1 = new DateTime($employee['start']);
								$date2 = new DateTime($employee['end']);
								$interval = $date1->diff($date2);
								echo "<tr>";
									echo "<td>".$employee['e_id']."</td>";
									echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
									echo "<td>".$employee['start']."</td>";
									echo "<td>".$employee['end']."</td>";
									echo "<td>".($interval->days+1)."</td>";
									echo "<td>".$employee['reason']."</td>";
									echo "<td>".$employee['status']."</td>";
									echo "<td>".$employee['type']."</td>";
									echo "<td>".$employee['cmnt']."</td>";
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
	<script type="text/javascript" src="assets/js/jquery-1.12.0.min.js"></script>
	<script>
		$(document).ready( function () {
			$('#table_leave').DataTable({
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