<?php
	session_start();
	include "./hashing/Encrypt.php";
	include "./hashing/Decrypt.php";
	require_once ('process/dbh.php');
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'user'){
			header('Location: index.php');
			exit;
		}
		$sql = "SELECT * from `employee` ORDER BY `e_id` ASC";
		$result = $conn->prepare($sql);
		$result->execute();
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
						<li><a href="aloginwel.php" class="active">HOME</a></li>
						<li><a href="addemp.php">Add Employee</a></li>
						<li><a href="empattend.php">Employee Attendance</a></li>
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
		<div class="container-fluid" style="height: 100%; min-height: 93vh;">
			<div class="table-responsive">
				<div style=" display: flex; justify-content: center;">
					<div style="padding-top: 100px; padding-bottom: 100px; width: 90%; display: flex; justify-content: center;">
						<table id="table_admin" class="display">  
							<thead>
								<tr>
									<th>Employee ID</th>
									<th>Picture</th>
									<th>Name</th>
									<th>Email</th>
									<th>Birthday</th>
									<th>Gender</th>
									<th>Contact</th>
									<th>NID</th>
									<th>Address</th>
									<th>Department</th>
									<th>Degree</th>
									<th>Options</th>
									<th>Action</th>
									
								</tr>
							</thead>
							<tbody>
								<?php
									while($employee = $result->fetch()) {
										//$enc = new Encrypt($employee['id']);
										//$employeeid = $enc->getOutput();
										echo "<tr>";
										echo "<td>".$employee['e_id']."</td>";
										echo "<td><img src='process/".$employee['pic']."' height = 60px width = 60px></td>";
										echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
										echo "<td>".$employee['email']."</td>";
										echo "<td>".$employee['birthday']."</td>";
										echo "<td>".$employee['gender']."</td>";
										echo "<td>".$employee['contact']."</td>";
										echo "<td>".$employee['nid']."</td>";
										echo "<td>".$employee['address']."</td>";
										echo "<td>".$employee['dept']."</td>";
										echo "<td>".$employee['degree']."</td>";
										echo "<td><a href=\"edit.php?id=$employee[id]\"><button type='button' class='btn btn-info'>Edit</button></a> | <a href=\"process\delete.php?id=$employee[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";
        								if($employee['status'] == '0'){
        									echo "<td><a href=\"process\inactive.php?id=$employee[id]\"<button type='button' class='btn btn-danger'>Inactive</button></a></td>";
        								}
        								elseif($employee['status'] == '1'){
        									echo "<td><a href=\"process\active.php?id=$employee[id]\"<button type='button' class='btn btn-success'>Active</button></a></td>";
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
	<?php
		include("./footer.php");
	?>
	<script>
		$(document).ready( function () {
			$('#table_admin').DataTable({
				
				dom: 'Bfrtip',
          		buttons: [
              	'copy', 'csv', 'excel', 'print', 'html5', {
                text: 'Reload',
                action: function ( e, dt, node, config ) {
                    location.reload();
                }
            }
          ],
          colReorder: true,
		  "info": true
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