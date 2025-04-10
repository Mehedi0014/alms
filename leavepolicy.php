<?php 
	session_start();
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
		if($_SESSION['role'] === 'admin'){
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
					<li><a href="eloginwel.php">HOME</a></li>
					<li><a href="applyleave.php">Apply Leave</a></li>
					<li><a href="logout.php">Log Out</a></li>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
		</header>
		<div style="padding-top: 100px; width: 100%;">
			<div class="container-fluid" style="height: 100%; min-height: 82.7v;">	
				<div id="divimg" style="">
				<h2 class="title" style="font-size: 44px; text-align: center;">Bangladesh and India Leave policy 2022</h2>
				<table id="table_leave_policy" class="display">
					<thead>
					<tr>
						<th></th>
						<th>Category</th>
						<th>Type</th>
						<th>Bangladesh</th>
						<th>India</th>
						<th>Comments</th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td>Public</td>
							<td>Mandatory</td>
							<td>13</td>
							<td>7</td>
							<td>This mustbe finalized by employees at the beginning of year</td>
						</tr>
						<tr>
							<td></td>
							<td>Public</td>
							<td>Optional</td>
							<td>3</td>
							<td>6</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td>Sick Leave</td>
							<td>Non Cumulative</td>
							<td>8</td>
							<td>8</td>
							<td>This cannot be carried forward to next calendar year</td>
						</tr>
						<tr>
							<td></td>
							<td>Earned Leave</td>
							<td>Conditional Cumulative</td>
							<td>8</td>
							<td>8</td>
							<td>Employees cannot take more than 3 ELs per quarter. This can be carried forward to 3 years upto total 30 days on an aggregate</td>
						</tr>
						<tr>
							<td></td>
							<td>Casual Leave</td>
							<td>Non Cumulative</td>
							<td>6</td>
							<td>8</td>
							<td>This cannot be carried forward to next calendar year</td>
						</tr>
						<tr>
							<td></td>
							<td>Special Leave</td>
							<td>Non Cumulative</td>
							<td>0</td>
							<td>1</td>
							<td>This cannot be carried forward to next calendar year. This is for special cases and would need approval before availing the same</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>Total</td>
							<td>38</td>
							<td>38</td>
							<td></td>
						</tr>
					</tbody>     
				</table>
				<h3><b>All leaves must be planned in advance except sick leaves or emergency leaves and must be approved by management a week in advance, otherwise leaves may not be approved at all.</b></h3>
				</div>
			</div>
      	</div>
	<?php
		include "./footer.php";
	?>
	<script>
		$(document).ready( function () {
			$('#table_leave_policy').DataTable({
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