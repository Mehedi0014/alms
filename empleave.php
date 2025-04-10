<?php
	session_start();
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'user'){
			header('Location: index.php');
			exit;
		}
	require_once ('process/dbh.php');
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
					<li><a href="lateattend.php">Late Attendance</a></li>
					<li><a href="empleave.php" class="active">Employee Leave</a></li>
					<li><a href="holiday.php">Holiday</a></li>
					<li><a href="setting.php">Setting</a></li>
					<li><a href="logout.php">Log Out</a></li>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header>
	<div style="padding-top: 100px; width: 100%;">
    	<div class="container-fluid" style="height: 100%; min-height: 82.7v;">	
			<div style="text-align: center;">
				<!--<div class="d-flex justify-content-center">-->
				<!--	<button class="btn btn-primary ml-2" id="btn_1" style="margin-right: 10px;" data-target="#details_1" onclick="return tabopen(this);">Leave Counter</button>-->
				<!--	<button class="btn btn-info" id="btn_2" style="/*margin-right: 10px;*/" data-target="#details_2" onclick="return tabopen(this);">Employee Leave</button>-->
				<!--</div>-->
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary ml-2" id="btn_1" style="margin-right: 10px;" data-target="#details_1" onclick="return tabopen(this);">Leave Counter</button>
						<button class="btn btn-info" id="btn_2" style="/*margin-right: 10px;*/" data-target="#details_2" onclick="return tabopen(this);">Employee Leave</button>
					</div>
					<div class="col-md-2">
						<input type="date" class="form-control" id="date_search" name="search-date">
					</div>
					<div class="col-md-1">
						<button class="btn btn-info" id="btn_3" style="/*margin-right: 10px;*/" data-target="#details_3">Show</button>
					</div>					
				</div>
				<div class="container-fluid" id="previous_year"  style="margin-top: 10px;display:none;">
					<div class="card" id="details_3">
            			<div id="divimg" style="">
              				<br>
              				<div class="card-head">Leave Counter Previous Year</div>
                			<br>
                			<div class="table-responsive">
    							<table id="table_empcount1" class="display">
    							<thead>
    								<tr>
    									<th>SL</th>
    									<th>Name</th>
    									<th>Sick Leave</th>
    									<th>Halfday Leave</th>
    									<th>Casual Leave</th>
    									<th>Special Leave</th>
    									<th>Earned Leave</th>
    									<th>Public Leave</th>
										<th>Total Leave</th>
    								</tr>
    							<thead>	
    							<tbody>
								</tbody>
    							</table>  
                			</div>
            			</div>
        			</div>
				</div>
				<div class="container-fluid"  style="margin-top: 10px;">
					<div class="tab_view card collapse" id="details_1">
            			<div id="divimg" style="">
              				<br>
              				<div class="card-head">Leave Counter of Employee</div>
                			<br>
                			<div class="table-responsive">
    							<table id="table_empcount" class="display">
    							<thead>
    								<tr>
    									<th>SL</th>
    									<th>Name</th>
    									<th>Sick Leave</th>
    									<th>Halfday Leave</th>
    									<th>Casual Leave</th>
    									<th>Special Leave</th>
    									<th>Earned Leave</th>
    									<th>Public Leave</th>
    									<th>Total Leave</th>
    								</tr>
    							<thead>	
    							<tbody>
    								<?php
    									$i = 0;
    									$sql="SELECT 
                                            	a.`id`,
                                                CONCAT(a.`firstName`, ' ', a.`lastName`) AS `name`,
                                                COALESCE(MAX(CASE WHEN (bb.`type` = 'Sick') THEN bb.`diff` END), 0) AS `Sick`,
                                                COALESCE(MAX(CASE WHEN (bb.`type` = 'Casual') THEN bb.`diff` END), 0) AS `Casual`,
                                                COALESCE((MAX(CASE WHEN (bb.`type` = 'Halfday') THEN bb.`diff` END) * 0.5), 0) AS `Halfday`,
                                                COALESCE(MAX(CASE WHEN (bb.`type` = 'Public') THEN bb.`diff` END), 0) AS `Public`,
                                                COALESCE(MAX(CASE WHEN (bb.`type` = 'Special') THEN bb.`diff` END), 0) AS `Special`,
                                                COALESCE(MAX(CASE WHEN (bb.`type` = 'Earned') THEN bb.`diff` END), 0) AS `Earned`
                                            FROM `employee` a
                                            LEFT JOIN (
                                            	/*SELECT b.`id`, SUM(DATEDIFF(b.`end`, b.`start`)+1) AS `diff`, b.`type`, b.`status` FROM `employee_leave` b WHERE DATE_FORMAT(`start` , 'Y') = DATE_FORMAT(CURRENT_DATE, 'Y') AND b.`status` = 'Approved' GROUP BY b.`id`,b.`type`*/
                                            	SELECT b.`id`, SUM(DATEDIFF(b.`end`, b.`start`)+1) AS `diff`, b.`type`, b.`status` FROM `employee_leave` b WHERE YEAR(`start`) = YEAR(CURDATE()) AND b.`status` = 'Approved' GROUP BY b.`id`,b.`type`
                                            ) bb 
                                            ON a.`id` = bb.`id` GROUP BY a.`id`";
    											$result = $conn->prepare($sql);
    											$result->execute();
    									while ($employee = $result->fetch()) {
    										$i++;
    										$total_leave = $employee['Sick'] + $employee['Halfday'] + $employee['Casual'] + $employee['Special'] + $employee['Earned'] + $employee['Public'];
    											echo "<tr>";
    											echo "<td>".$i."</td>";
    											echo "<td>".$employee['name']."</td>";
    											echo "<td>".$employee['Sick']."</td>";
    											echo "<td>".$employee['Halfday']."</td>";
    											echo "<td>".$employee['Casual']."</td>";
    											echo "<td>".$employee['Special']."</td>";
    											echo "<td>".$employee['Earned']."</td>";
    											echo "<td>".$employee['Public']."</td>";
    											echo "<td>".$total_leave."</td>";
    										echo "</tr>";
    									}
    								?>
    								</tbody>
    							</table>  
                			</div>
            			</div>
        			</div>
				</div>
        		<div class="tab_view card" id="details_2">
          			<div class="card-body">
            			<div class="card-head">Employee Leave List</div>
						<div class="card-body">
							<div class="container-fluid" style="height: 100%;">
								<div id="divimg" style="">
									<table id="table_emp" class="display">
										<thead>
											<tr>
												<th>SL</th>
												<th>Employee ID</th>
												<th>Name</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Total Days</th>
												<th>Reason</th>
												<th>Status</th>
												<th>Options</th>
											</tr>
										<thead>	
										<tbody>
											<?php
												$sql = "SELECT 
															a.id, 
															a.e_id, 
															a.firstName, 
															a.lastName, 
															b.start, 
															b.end, 
															b.reason, 
															b.status, 
															b.token 
														FROM employee AS a, employee_leave AS b
														WHERE a.id = b.id AND DATE_FORMAT(`start`, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y') 
														order by b.token DESC";
												$result = $conn->prepare($sql);
												$result->execute();
												$i = 0;
												while ($employee = $result->fetch()) {
													$i++;
													$date1 = new DateTime($employee['start']);
													$date2 = new DateTime($employee['end']);
													$interval = $date1->diff($date2);
													echo "<tr>";
														echo "<td>".$i."</td>";
														echo "<td>".$employee['e_id']."</td>";
														echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
														echo "<td>".$employee['start']."</td>";
														echo "<td>".$employee['end']."</td>";
														echo "<td>".(($interval->days)+1)."</td>";
														echo "<td>".$employee['reason']."</td>";
														echo "<td>".$employee['status']."</td>";
														if($employee['status'] == 'Approved'){
															echo "<td><a href=\"cancel.php?id=$employee[id]&token=$employee[token]\"<button type='button' class='btn btn-danger'>Cancel</button></a></td>";
														}
														elseif($employee['status'] == 'Cancelled'){
															echo "<td></td>";
														}
														else{
															echo "<td><a href=\"approve.php?id=$employee[id]&token=$employee[token]\"<button type='button' class='btn btn-success'>Approve</button></a> | <a href=\"cancel.php?id=$employee[id]&token=$employee[token]\"<button type='button' class='btn btn-danger'>Cancel</button></a></td>";
														}
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
			$('#table_empcount').DataTable({
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
		$('#table_emp').DataTable( {
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
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#btn_3").click(function(){
				var date_val = document.getElementById('date_search').value;
				$.ajax({
					url:'leave_search.php',
					method:'POST',
					dataType: 'JSON',
					data:{date_search:date_val},
					success:function(response){
						var len = response.length;
						for(var i = 0;i<len;i++)
						{
							var id = response[i].id;
							var name = response[i].name;
							var sick_leave = response[i].sick;
							var halfday = response[i].halfday;
							var casual = response[i].casual;
							var special = response[i].special;
							var earned = response[i].earned;
							var public = response[i].public;
							var total = response[i].total;
							var tr_str = "<tr>"+
										"<td>"+id+"</td>"+
										"<td>"+name+"</td>"+
										"<td>"+sick_leave+"</td>"+
										"<td>"+halfday+"</td>"+
										"<td>"+casual+"</td>"+	
										"<td>"+special+"</td>"+	
										"<td>"+earned+"</td>"+	
										"<td>"+public+"</td>"+	
										"<td>"+total+"</td>"+
										"</tr>"	
							$("#table_empcount1 tbody").append(tr_str);
						}
						$("#previous_year").show();
					}
				});
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