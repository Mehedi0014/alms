<?php 
	session_start();
	require_once ('process/dbh.php');
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'user'){
			header('Location: index.php');
			exit;
		}
	include "./header.php";
?>
	<style>
		.card-head {
		background: #10640526;
		border-bottom: 1px solid #ededed;
		padding: 20px;
		font-size: 25px;
		font-weight: 900;
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
					<li><a href="aloginwel.php">HOME</a></li>
					<li><a href="addemp.php">Add Employee</a></li>
					<li><a href="empattend.php">Employee Attendance</a></li>
					<li><a href="lateattend.php">Late Attendance</a></li>
					<li><a href="empleave.php">Employee Leave</a></li>
					<li><a href="holiday.php" class="active">Holiday</a></li>
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
					<button class="btn btn-primary ml-2" id="btn_1" style="margin-right: 10px;" data-target="#details_1" onclick="return tabopen(this);">Bangladesh</button>
					<button class="btn btn-success ml-2" id="btn_2" style="margin-right: 10px;" data-target="#details_2" onclick="return tabopen(this);">India</button>
					<button class="btn btn-info" id="btn_3" style="/*margin-right: 10px;*/" data-target="#details_3" onclick="return tabopen(this);">Both</button>
				</div>
				<div class="container"  style="margin-top: 10px;">
					<div class="tab_view card collapse" id="details_1">
						<div class="bg-blue p-t-100 p-b-100 font-robo">
							<div class="wrapper wrapper--w680">
								<div class="card card-1">
									<div class="card-heading"></div>
									<div class="card-body">
										<h2 class="title">Add Holiday for Bangladesh</h2>
										<div style="display: flex;">
											<div class="col-md-12" style="text-align: center;">
												<form class="form-horizontal well" action="process/import.php" method="post" name="upload_excel" enctype="multipart/form-data">
													<div class="row">
														<div class="col-md-4">
															<input type="file" name="file" id="file" class="input-large">			
														</div>
														<div class="col-md-4">
															<button type="submit" id="submit" name="Import" class="btn btn-info button-loading" data-loading-text="Loading...">Upload</button>						
														</div>
														<div class="col-md-4">
														<a href="./assets/holiday.csv" type="button" class="btn btn-primary">Download</a>
														</div>
													</div>
												</form>	
											</div>
										</div></br></br>
										<form action="process/addholidayprocess.php?" method="POST">
											<div class="col-2">
												<p>Date</p>
												<div class="input-group">
													<input class="input--style-1" type="date" placeholder="date" name="date">
												</div>
											</div>
											<div class="input-group">
												<input class="input--style-1" type="text" placeholder="occasion" name="occasion">
											</div>
												<input type="hidden" placeholder="City" name="city" value="bd">
											<div class="p-t-20">
												<button class="btn btn--radius btn--green" type="submit">Submit</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="container" style="margin-bottom: 100px">
							<h1 class="text-center">Holiday List</h1>
							<hr>
							<br>
							<table id="table_holidaybd" class="display">
								<thead>
									<tr>
										<th>Sl.</th>
										<th>Date</th>
										<th>Occasion</th>
										<th>Option</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sal = "SELECT * FROM `holiday` WHERE `city` = 'bd' AND DATE_FORMAT(`date`, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y') ORDER BY date ASC";
										$result = $conn->prepare($sal);
										$result->execute();
										$j = 0;
										while ($holiday = $result->fetch()) {
											$j++;
											echo "<tr>";
												echo "<td>".$j."</td>";
												echo "<td>".$holiday['date']."</td>";
												echo "<td>".$holiday['occasion']."</td>";
												echo "<td><a href=\"process/deleteholiday.php?id=$holiday[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab_view card collapse" id="details_2">
						<div class="bg-blue p-t-100 p-b-100 font-robo">
							<div class="wrapper wrapper--w680">
								<div class="card card-1">
									<div class="card-heading"></div>
									<div class="card-body">
										<h2 class="title">Add Holiday for India</h2>
										<div style="display: flex;">
											<div class="col-md-12" style="text-align: center;">
												<form class="form-horizontal well" action="process/import.php" method="post" name="upload_excel" enctype="multipart/form-data">
													<div class="row">
														<div class="col-md-4">
															<input type="file" name="file" id="file" class="input-large">			
														</div>
														<div class="col-md-4">
															<button type="submit" id="submit" name="Import" class="btn btn-info button-loading" data-loading-text="Loading...">Upload</button>						
														</div>
														<div class="col-md-4">
														<a href="./assets/holiday.csv" type="button" class="btn btn-primary">Download</a>
														</div>
													</div>
												</form>	
											</div>
										</div></br></br>
										<form action="process/addholidayprocess.php?" method="POST">
											<div class="col-2">
												<p>Date</p>
												<div class="input-group">
													<input class="input--style-1" type="date" placeholder="date" name="date">
												</div>
											</div>
											<div class="input-group">
												<input class="input--style-1" type="text" placeholder="occasion" name="occasion">
												<input type="hidden" placeholder="City" name="city" value="ind">
											</div>
											<div class="p-t-20">
												<button class="btn btn--radius btn--green" type="submit">Submit</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="container" style="margin-bottom: 100px">
							<h1 class="text-center">Holiday List</h1>
							<hr>
							<br>
							<table id="table_holidayind" class="display">
								<thead>
									<tr>
										<th>Sl.</th>
										<th>Date</th>
										<th>Occasion</th>
										<th>Option</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sal = "SELECT * FROM `holiday` WHERE `city` = 'ind' AND DATE_FORMAT(`date`, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y') ORDER BY date ASC";
										$result = $conn->prepare($sal);
										$result->execute();
										$j = 0;
										while ($holiday = $result->fetch()) {
											$j++;
											echo "<tr>";
												echo "<td>".$j."</td>";
												echo "<td>".$holiday['date']."</td>";
												echo "<td>".$holiday['occasion']."</td>";
												echo "<td><a href=\"process/deleteholiday.php?id=$holiday[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab_view card" id="details_3">
						<div class="card-head">
							Holiday of both Country
						</div>
						<div class="card-body">
						<div class="container">
							<div class="row">
								<div class="col-md-6">
									<div class="card">
										<div class="card-head">
											Bangladesh
										</div>
										<div class="card-body">
											<table id="table_holidayind_1" class="display">
												<thead>
													<tr>
														<th>Sl.</th>
														<th>Date</th>
														<th>Occasion</th>
														<th>Option</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$sal = "SELECT * FROM `holiday` WHERE `city` = 'bd' AND DATE_FORMAT(`date`, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y') ORDER BY date ASC";
														$result = $conn->prepare($sal);
														$result->execute();
														$j = 0;
														while ($holiday = $result->fetch()) {
															$j++;
															echo "<tr>";
																echo "<td>".$j."</td>";
																echo "<td>".$holiday['date']."</td>";
																echo "<td>".$holiday['occasion']."</td>";
																echo "<td><a href=\"process/deleteholiday.php?id=$holiday[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";
															echo "</tr>";
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card">
										<div class="card-head">
											India
										</div>
										<div class="card-body">
											<table id="table_holidayind_2" class="display">
												<thead>
													<tr>
														<th>Sl.</th>
														<th>Date</th>
														<th>Occasion</th>
														<th>Option</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$sal = "SELECT * FROM `holiday` WHERE `city` = 'ind' AND DATE_FORMAT(`date`, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y') ORDER BY date ASC";
														$result = $conn->prepare($sal);
														$result->execute();
														$j = 0;
														while ($holiday = $result->fetch()) {
															$j++;
															echo "<tr>";
																echo "<td>".$j."</td>";
																echo "<td>".$holiday['date']."</td>";
																echo "<td>".$holiday['occasion']."</td>";
																echo "<td><a href=\"process/deleteholiday.php?id=$holiday[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";
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
				</div>
			</div>
		</div>
	</div>
	<?php
		include "./footer.php";
	?>
	<script>
		$(document).ready( function () {
			$('#table_holidaybd').DataTable({
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
			$('#table_holidayind').DataTable({
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
			$('#table_holidayind_1').DataTable({
				
			});
			$('#table_holidayind_2').DataTable({
				
          colReorder: true
			});

		});
		
		function tabopen(prop){
			var clicked_btn = $(prop).data("target");
			$(".tab_view").removeClass('show');
			$(".tab_view").addClass('collapse');
			$(clicked_btn).addClass("show");
		}

	</script>
</html>		
<?php 
}else{
	header('location: logout.php');
	exit;
}
?>