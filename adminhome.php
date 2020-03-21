<?php
//cron updation, what else did sir say?
include 'header.php';
//echo date("Y");
session_start();

// if(!isset($_SESSION['loggedInUser'])){
// 	header("location:index.php");
// }
//$name=$_SESSION['name'];

include_once("includes/connection.php");

if(isset($_POST['newthresh'])) {
	$newt = $_POST['thre'];
	$com = $_POST['com'];

	$q = "UPDATE commdet SET thresh_days = '$newt' where name = '$com'";
	$re = mysqli_query($conn,$q);
	if($re) {
		header("location: admin.php");
	}
}

if(isset($_POST['suspendstud'])) {
	if(!empty($_POST['batchoperstud'])) {
		foreach($_POST['batchoperstud'] as $id) {
			//echo $id;
			$q = "UPDATE userdet SET suspend = 1,sustime = YEAR(CURDATE()) where id = '$id'";
			$r = mysqli_query($conn,$q);
		}
	}
}

if(isset($_POST['delad'])) {
	$id = $_POST['adid'];
	$q = "DELETE FROM admindet WHERE id = '$id' ";
	$r = mysqli_query($conn,$q);
}

if(isset($_POST['makead'])) {
	if(!empty($_POST['batchoper'])) {
		foreach($_POST['batchoper'] as $idfac) {
			$qu = "SELECT * from admindet where id = '$idfac' ";
			if(mysqli_query($conn,$qu) == true) {
				echo "<script>alert('They are already an admin.');</script>";
			}
			else {
				$q = "SELECT * from facdet where id = '$idfac' ";
				$row = mysqli_fetch_assoc(mysqli_query($conn,$q));
				$lname = $row['lname'];
				$fname = $row['fname'];
				$fathername = $row['fathername'];
				$mothername = $row['mothername'];
				$email = $row['email'];

				$q = "INSERT INTO admindet(lname,fname,fathername,mothername,id,email) VALUES ('$lname','$fname','$fathername','$mothername','$idfac','$email')";
				$r = mysqli_query($conn,$q);
			}
		}
	}
}

if(isset($_POST['suspend'])) {
	if(!empty($_POST['batchoper'])) {
		foreach($_POST['batchoper'] as $idfac) {
			//echo $id;
			$q = "UPDATE facdet SET suspend = 1,sustime = YEAR(CURDATE()) where id = '$idfac'";
			$r = mysqli_query($conn,$q);
		}
	}
}

if(isset($_POST['dropstud'])) {
	if(!empty($_POST['batchoperstud'])) {
		foreach ($_POST['batchoperstud'] as $id) {
			$q = "SELECT * from userdet WHERE id = '$id'";
			$row = mysqli_fetch_assoc(mysqli_query($conn,$q));
			$c = $row['class'];
			if($c == "SY") $c = "FY";
			else if($c == "TY") $c = "SY";
			else if($c == "LY") $c = "TY";
			else if($c == "Passed out") $c = "LY";

			$qu = "UPDATE userdet SET class = '$c' WHERE id = '$id' ";
			$ru = mysqli_query($conn,$qu);
		}
	}
}

$q = "SELECT * from admindet WHERE email = '".$_SESSION['loggedInUser']."' ";
$row = mysqli_fetch_assoc(mysqli_query($conn,$q));
$lname = $row['lname'];
$fname = $row['fname'];
$fathername = $row['fathername'];

?>
<!DOCTYPE HTML>
<html>
<h3 align = 'center'>Admin Dashboard</h3>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>HI, ".$lname." ".$fname." ".$fathername."!</h4>" ?>

<style>
button {
	padding: 2%;
	background: rgba(220,220,220,0.5);
	border-width: 5px;
	border-style: inset outset outset inset;
}

button:hover {
	background: rgba(250,250,250,1);
}
</style>

<table class = "table table-stripped">
	<form method = "POST">
		<thead>
			<tr>
				<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'import'> Import student/committee database  </button> </th>
				<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'alter'> Alter student/committee database  </button> </th>
				<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'editcomm'> Change committee details </button> </th>
				<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'editcat'> Change category details </button> </th>
				<th style="text-align:center;"> <button type = 'submit' class="btn btn-info" name = 'toggle' value = 'editad'> Change admin details </button> </th>
			</tr>
		</thead>
	</form>

	<tbody>
		<tr><td colspan = '5'>
			<div align = "center">
				<?php
					if(isset($_POST['toggle'])) {
						if($_POST['toggle'] == 'import')
							echo "<h4> Import student/committee database </h4>";
						else if($_POST['toggle'] == 'alter')
							echo "<h4> Alter student/committee database </h4>";
						else if($_POST['toggle'] == 'editcomm')
							echo "<h4> Change committee details </h4>";
						else if($_POST['toggle'] == 'editcat')
							echo "<h4> Change category details </h4>";
						else if($_POST['toggle'] == 'editad')
							echo "<h4> Change admin details </h4>";
					}
					// else
					// 	echo "<h4> Choose an action to perform. </h4>";
				?>
			</div><br>
			<?php
				if(isset($_POST['toggle'])) {
					if($_POST['toggle'] == 'editcomm') { 
						
			?>
			<div class = "col-sm-4"></div>
			<div class = "col-sm-4" float = "right">
				<table class = "table table-striped">
					<tbody>
						<form method = "POST" action = "commdet.php">
							<?php
								$q = "SELECT name,email from commdet";
								$re = mysqli_query($conn,$q);

								while($row = mysqli_fetch_assoc($re)) {
									echo "<tr><button type = 'submit' class = 'btn-block' name = 'comms' value = '".$row['email']."'><h4>" .$row['name']."</h4><h5>" .$row['email']."</h5></button></tr>";
								}
							?>
							<tr>
								<button type = "submit" class = "btn-block" name = "addcomm">
									<h4>Add a new committee</h4>
								</button>
							</tr>
						</form>
					</tbody>
				</table>
			</div>	
			<?php
			 	}
			 	else if($_POST['toggle'] == 'import') {
			?>
			<div>
				<div align = "center">
					<label> Import student database </label>
						<form method = "post" action = "addcsv.php">
							<input type = "file" id = "filest" name = "filest"><br>
							<button type = "submit" name = "sub" id = "sub">Import</button>
						</form>
						<br><br>
						<label> Import faculty database </label>
						<form method = "post" action = "addcsv.php">
							<input type = "file" id = "file" name = "file"><br>
							<button type = "submit" name = "subf" id = "subf">Import</button>
						</form>
				</div>
			</div>
			<?php
				}
				else if($_POST['toggle'] == 'editcat') {
			?>
			<div class = "col-sm-4"></div>
			<div class = "col-sm-4" float = "right">
				<table class = "table table-striped">
					<tbody>
						<form method = "POST" action = "catdet.php">
							<?php
								$q = "SELECT category from catdet";
								$re = mysqli_query($conn,$q);

								while($row = mysqli_fetch_assoc($re)) {
									echo "<tr><button type = 'submit' class = 'btn-block' name = 'cats' value = '".$row['category']."'><h4>" .$row['category']."</h4></button></tr>";
								}
							?>
							<tr>
								<button type = "submit" class = "btn-block" name = "addcat">
									<h4>Add a new category</h4>
								</button>
							</tr>
						</form>
					</tbody>
				</table>
			</div>	
			<?php
				}
				else if($_POST['toggle'] == 'alter') {
			?>
				<form method = "POST">
					<div align = "center">
						<label>Select whose data to alter: </label>
						<select name = "whose" required>
							<option value = "">Select</option>
							<option value = "stud">Student</option>
							<option value = "emp">Employee</option>
						</select><br>
						<label>Select year: (Only for student database) </label>
						<select name = "class">
							<option value = "">Select</option>
							<option value = "FY">FY</option>
							<option value = "SY">SY</option>
							<option value = "TY">TY</option>
							<option value = "LY">LY</option>
						</select><br>
						<label>Select department: (For student or employee database)</label>
						<select name = "depar">
							<option value = "">Select</option>
							<option value = "COMP">COMP</option>
							<option value = "IT">IT</option>
							<option value = "ETRX">ETRX</option>
							<option value = "EXTC">EXTC</option>
							<option value = "MECH">MECH</option>
						</select><br>
						<label>Select designation: (Only for employee database)</label>
						<select name = "desig">
							<option value = "">Select</option>
							<option value = "prof">Professor</option>
							<option value = "asstprof">Assistant Professor</option>
						</select>
					</div>
					<div align = "center">
						<button type = "submit" name = "alt" id = "alt" class = "btn btn-info"> <h4>Display Data</h4></button>
					</div>
				</form>
			<?php
				}
				else if($_POST['toggle'] == 'editad') {
			?>
					<form method = "POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
						<?php
						echo '<table class="table table-striped">
								<thead class = "thead-dark">
									<tr>
										<th>Last Name</th>
										<th>First Name</th>
										<th>Father Name</th>
										<th>Id</th>
										<th>Email</th>
										<th>Delete</th>
									</tr>';
						$que = "SELECT * from admindet";
						//echo $que;
						
						$res = mysqli_query($conn,$que);
						while($row = mysqli_fetch_assoc($res)) {
							//$_SESSION['updstud'] = $row['id'];
							echo "<tr>";
							echo "<td>".$row['lname']."</td>";
							echo "<td>".$row['fname']."</td>";
							echo "<td>".$row['fathername']."</td>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['email']."</td>";
							echo "<td><input type = 'hidden' name = 'adid' id = 'adid' value = '".$row['id']."'>
								<button type = 'submit' name = 'delad' id = 'delad' class = 'btn btn-danger'>Delete Admin </button> ";	
							echo "</tr>";
						}
						echo "</table>";
						echo "</form>";
				}
			}
			?>

		</tr>
	</tbody>
</table>
	<!-- Trigger the modal with a button -->
	<?php
	if(isset($_POST['alt'])) {
	if($_POST['whose'] == "stud") {
		$cl = $_POST['class'];
		$dp = $_POST['depar'];
		if($_POST['desig'] != "") echo "<script> alert('Could not apply designation filter. Class and department filters will be applied.');</script>";
		?>
		<form method = "POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		echo '<table id = "studdb" class="table table-striped">
				<thead class = "thead-dark">
					<tr>
						<th>Select option</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Father Name</th>
						<th>RollNo</th>
						<th>Email</th>
						<th>Current Year</th>
						<th>Department</th>
						<th>Alter details</th>
					</tr>';
		if($cl!="" && $dp!="") $que = "SELECT * from userdet WHERE class = '$cl' AND dept = '$dp' ORDER BY id";
		else if($_POST['class']=="" && $_POST['depar']!="") $que = "SELECT * from userdet WHERE dept = '$dp' ORDER BY id";
		else if($_POST['class']!="" && $_POST['depar']=="") $que = "SELECT * from userdet WHERE class = '$cl' ORDER BY id";
		else $que = "SELECT * from userdet ORDER BY id";
		//echo $que;
		
		$res = mysqli_query($conn,$que);
		while($row = mysqli_fetch_assoc($res)) {
			$i=0;
			//$_SESSION['updstud'] = $row['id'];
			echo "<tr>";
			echo "<td><input type = 'checkbox' name = 'batchoperstud[]' value = '".$row['id']."'></td>";
			echo "<td>".$row['lname']."</td>";
			echo "<td>".$row['fname']."</td>";
			echo "<td>".$row['fathername']."</td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['email']."</td>";
			echo "<td>".$row['class']."</td>";
			echo "<td>".$row['dept']."</td>";
			echo "<td>
						<button type = 'button' name = 'altstudbutton' id = 'altstudbutton' class = 'btn-block' data-toggle = 'modal' data-target = '#studentdetails".$row['id']."'>
						Alter</button>
						<div style = 'color: #000000;' class='modal' id='studentdetails".$row['id']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Student details:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body' style = 'line-height:30px;'>
														<form method = 'POST' class = 'form-horizontal'>
														
															<label>Last name:</label>
															<input type = 'text' value = '".$row['lname']."' name = 'lname' required><br>
															<label>First name:</label>
															<input type = 'text' value = '".$row['fname']."' name = 'fname' required><br>
															<label>Father's name:</label>
															<input type = 'text' value = '".$row['fathername']."' name = 'fathername'><br>
															<label>Mother's name:</label>
															<input type = 'text' value = '".$row['mothername']."' name = 'mothername'> <br>
															<label>RollNo:</label>
															<input type = 'text' value = '".$row['id']."' name = 'rollno' required><br>
															<label>Email:</label>
															<input type = 'text' value = '".$row['email']."' name = 'email' required><br>
															<label>Class:</label>
															<input type = 'text' value = '".$row['class']."' name = 'class' required><br>
															<label>Department:</label>
															<input type = 'text' value = '".$row['dept']."' name = 'dept' required><br>
															<label>Join Year:</label>
															<input type = 'text' value = '".$row['joinyear']."' name = 'join' required><br>
															<input type = 'hidden' value = '".$row['email']."' name = 'selem'>
															<button type = 'submit' class = 'btn-success' name = 'upd' id = 'upd'>Update Details</button>
														</form>
													</div>
												  </div>  
												</div>
										  </div>
				</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<div align = 'center'>
			<button type = 'submit' name = 'suspendstud' class = 'btn btn-info'> Suspend </button>
			<button type = 'submit' name = 'dropstud' class = 'btn btn-info'>Year Drop Students </button> </div></form>";
	}
	else if($_POST['whose'] == "emp") {
		if($_POST['desig'] == "prof") $desg = "Professor";
		else if($_POST['desig'] == "asstprof") $desg = "Asst. Professor";
		$dp = $_POST['depar'];
		if($_POST['class'] != "") echo "<script> alert('Could not apply year filter. Designation and department filters will be applied.');</script>";
		?>
		<form method = "post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<?php
		echo '<table class = "table table-striped">
				<thead class = "thead-dark">
					<tr>
						<th>Select option</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Father Name</th>
						<th>ID</th>
						<th>Email</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Alter details</th>
					</tr>';
		if($_POST['desig']!="" && $_POST['depar']!="") $que = "SELECT * from facdet WHERE designation = '$desg' AND dept = '$dp'";
		else if($_POST['desig']=="" && $_POST['depar']!="") $que = "SELECT * from facdet WHERE dept = '$dp'";
		else if($_POST['desig']!="" && $_POST['depar']=="") $que = "SELECT * from facdet WHERE designation = '$desg'";
		else $que = "SELECT * from facdet";
		$res = mysqli_query($conn,$que);
		?>
		<?php
		while($row = mysqli_fetch_assoc($res)) {
			//$_SESSION['upfac'] = $row['id'];
			echo "<tr>";
			echo "<td><input type = 'checkbox' name = 'batchoper[]' value = '".$row['id']."'></td>";
			echo "<td>".$row['lname']."</td>";
			echo "<td>".$row['fname']."</td>";
			echo "<td>".$row['fathername']."</td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['email']."</td>";
			echo "<td>".$row['dept']."</td>";
			echo "<td>".$row['designation']."</td>";
			echo "<td>
						<button type = 'button' name = 'altempbutton' id = 'altempbutton' class = 'btn-block' data-toggle = 'modal' data-target = '#empdetails".$row['id']."'>
						Alter</button>
						<div style = 'color: #000000;' class='modal' id='empdetails".$row['id']."' role='dialog'>
												<div class='modal-dialog'>
												
												  <!-- Modal content-->
												  <div class='modal-content'>
													<div class='modal-header'>
													  <h4 class='modal-title'>Employee details:</h4>
													  <button type='button' class='close' data-dismiss='modal'>&times;</button>
													</div>
													<div class='modal-body' style = 'line-height:30px;'>
														<form method = 'POST' class = 'form-horizontal'>
														
															<label>Last name:</label>
															<input type = 'text' value = '".$row['lname']."' name = 'lname' required><br>
															<label>First name:</label>
															<input type = 'text' value = '".$row['fname']."' name = 'fname' required><br>
															<label>Father's name:</label>
															<input type = 'text' value = '".$row['fathername']."' name = 'fathername'><br>
															<label>Mother's name:</label>
															<input type = 'text' value = '".$row['mothername']."' name = 'mothername'> <br>
															<label>ID:</label>
															<input type = 'text' value = '".$row['id']."' name = 'id' required><br>
															<label>Email:</label>
															<input type = 'text' value = '".$row['email']."' name = 'email' required><br>
															<label>Department:</label>
															<input type = 'text' value = '".$row['dept']."' name = 'dept' required><br>
															<label>Designation:</label>
															<input type = 'text' value = '".$row['designation']."' name = 'designation' required><br>
															<label>Join Date:</label>
															<input type = 'text' value = '".$row['joindate']."' name = 'join' required><br>
															<input type = 'hidden' value = '".$row['email']."' name = 'selem'>
															<button type = 'submit' class = 'btn-success' name = 'updfac' id = 'updfac'>Update Details</button>
														</form>
													</div>
												  </div>  
												</div>
										  </div>
				</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<br>
			<button type = 'submit' name = 'suspend' class = 'btn btn-info'> Suspend </button>
			<button type = 'submit' class = 'btn btn-info' name = 'makead' id = 'makead'>Make Admin</button></form>";
	}
}

if(isset($_POST['upd'])) {
	$lname = $_POST['lname'];
	$fname = $_POST['fname'];
	$fathername = $_POST['fathername'];
	$mothername = $_POST['mothername'];
	$rollno = $_POST['rollno'];
	$email = $_POST['email'];
	$dept = $_POST['dept'];
	$class = $_POST['class'];
	$join = $_POST['join'];
	$oldem = $_POST['selem'];

	$query = "UPDATE userdet SET lname = '$lname' , fname = '$fname' , fathername = '$fathername' , mothername = '$mothername' , id = '$rollno' , email = '$email' , class = '$class' , dept = '$dept' , joinyear = '$join' where email = '$oldem' ";
	$r = mysqli_query($conn,$query);
	//if($r) echo "Updated";
}
if(isset($_POST['updfac'])) {
	$lname = $_POST['lname'];
	$fname = $_POST['fname'];
	$oldem = $_POST['selem'];
	$fathername = $_POST['fathername'];
	$mothername = $_POST['mothername'];
	$id = $_POST['id'];
	$email = $_POST['email'];
	$dept = $_POST['dept'];
	$des = $_POST['designation'];
	$join = $_POST['join'];

	$query = "UPDATE facdet SET lname = '$lname' , fname = '$fname' , fathername = '$fathername' , mothername = '$mothername' , id = '$id' , email = '$email' , dept = '$dept' , designation = '$des' , joindate = '$join' where email = '$oldem' ";
	$r = mysqli_query($conn,$query);
	//if($r) echo "Updated";
	$q = "SELECT * from admindet where email = '$oldem' ";
	if(mysqli_query($conn,$q) == true) {
		$qu = "UPDATE admindet SET lname = '$lname',fname = '$fname',fathername = '$fathername',mothername = '$mothername',id = '$id',email = '$email' ";
		$re = mysqli_query($conn,$qu);
	}
}

	?>
</html>