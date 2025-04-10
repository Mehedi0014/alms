<?php
  session_start();
  if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    if($_SESSION['role'] === 'user'){
      header('Location: index.php');
      exit;
    }
    include "./header.php";
    require_once ('process/dbh.php');
?>  
    <style>
        .input-group label {
            position: absolute;
            font-size: 20px;
            width: 100%;
            z-index: 1;
            transition: 1s ease-in-out;
            text-align: left;
        }
        .input--style-1{
            background: transparent;
            position: relative;
            z-index: 2;
        }


        .animation{
            font-size: 6px;
            transform: translate(0, -20px);
            
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
            <li><a href="addemp.php" >Add Employee</a></li>
            <li><a href="empattend.php">Employee Attendance</a></li>
            <li><a href="lateattend.php">Late Attendance</a></li>
            <li><a href="empleave.php">Employee Leave</a></li>
            <li><a href="holiday.php">Holiday</a></li>
            <li><a href="setting.php" class="active">Setting</a></li>
            <li><a href="logout.php">Log Out</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        </div>
    </header>
    <div style="padding-top: 100px; width: 100%;">
        <div class="container" style="height: 100%; min-height: 82.7vh; text-align: center; margin: 0 auto;">
            <div class="card" style="width: 100%; max-width: 30rem; margin: 0 auto;">
                <div class="card-body">
                    <div class="card-body-1">
                        <h2 class="title">Create New Admin</h2>
                        <form id = "registration" action="process/createadmin.php" method="POST">
                        <div class="input-group">
                            <label for="" class="animation">Enter Email</label>
                            <input class="input--style-1" type="email"  name="email">
                        </div>
                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit" name="update">Submit</button>
                        </div>
                        </form>
                        <br>
                        <br>
                        <button class="btn btn--radius btn--green" onclick="window.location.href = 'changeadminpass.php?id=<?php echo $id?>';">Change Password</button>
                    </div>
                </div>
            </div>
            <div style=" display: flex; justify-content: center;">

					<div style="padding-top: 100px; padding-bottom: 100px; width: 90%; display: flex; justify-content: center;">

						<table id="table_admin" class="displa">  

							<thead>

								<tr>

									<th>SL.</th>

									<th>Email</th>

									<th>Options</th>

								</tr>

							</thead>

							<tbody>

								<?php
                                    $sql="SELECT * FROM `alogin`";
                                    $result = $conn->prepare($sql);
                                    $result->execute();
                                    $i=0;

									while($alogin = $result->fetch()) {

										$i++;
                                        echo "<tr>";
                                            echo "<td>".$i."</td>";
                                            echo "<td>".$alogin['email']."</td>";
                                            echo "<td><a href=\"process/deleteadmin.php?id=$alogin[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><button type='button' class='btn btn-danger'>Delete</button></a></td>";

										echo "</tr>";

									}

								?>

							</tbody>

						</table>

					</div>

				</div>
        </div>
    </div>
    <script>
        $(document).ready({
            $(".input--style-1").on("focus", function(){
                console.log(this);
            });
        });
    </script>

</html>

<?php 

	}else{

		header('location: logout.php');

		exit;

	}