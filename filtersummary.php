<?php include 'header.php';?>
<?php
session_start();
//ob_start();

if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}

$_SESSION['previousp'] = basename($_SERVER['PHP_SELF']);

include_once("includes/connection.php");

if(isset($_POST['clearfilter'])) {
	unset($_SESSION['rollfilter']);
	header('location: princi.php');
}

if(isset($_POST['clearfilters'])) {
	unset($_SESSION['filtercond']);
	unset($_SESSION['filterarray']);
	header('location: princi.php');
}

?>

<!DOCTYPE HTML>

<html>

<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
<title>Search/Summary</title>
</head>
<body>
<br>

<div style = "float: right;">
	<form action = "princi.php" method = "POST">
		<button type = "submit" class="btn btn-success" name = "back" value = "back"> Back to Dashboard  </button>
	</form>
</div>

<br>
<br>

<table class = "table table-striped">
	<thead class = "thead-dark">
		<th colspan = "3"> Filter by roll number: </th>
	</thead>
	<tbody>
		<tr>
			<form class="form-inline" method = "POST">
				<td style="text-align:center;"><div class="form-group">
				<label for = "role"> ROLE: </label>
				<select name = "role" id = "role" style = "height: 5%;" class="form-control" required>
						<option value = ""> Select a role </option>
						<option value = "Student"> Student </option>
						<option value = "Employee"> Employee </option>
				</select>
				</div></td>
				<td style="text-align:center;"><div class="form-group">
				
				<label for = "rollfilter"> ROLL/ID NUMBER: </label>
					<input type="text" class="form-control" name = "rollfilter" id="rollfilter" value = "" placeholder = "Enter Roll/ID Number" required>
				</div></td>
				<td style="text-align:center;"><div class="form-group">
				<label for = "rollsub"> &ensp; </label>
					<button type="submit" class="btn btn-success form-control" name = "rollsub" id = "rollsub"> Apply Filter </button>
				</div></td>
			</form>
		</tr>
	</tbody>
</table>

<table class = "table table-striped">
	<thead class = "thead-dark">
		<th colspan = "4"> Apply Multiple Filters: </th>
	</thead>
	<tbody>
		<form class="form-inline" method = "POST">
			<tr>
				<td style="text-align:center;"><div class="form-group">
				<label for = "role"> ROLE: </label>
				<select name = "role" id = "role" style = "height: 5%;" class="form-control" required>
					<option value = ""> Select a role </option>
					<option value = "Student"> Student </option>
					<option value = "Employee"> Employee </option>
					<option value = "Both"> Both </option>
				</select>
				<br>
				<label for = "fromdate">FROM: </label>
				<input type="date" class="form-control" name = "fromdate" id="fromdate" value = "">
				</div></td>
				
				<td style="text-align:center;"><div class="form-group">
				<label for = "class"> CLASS: </label>
				<select name = "class" class="form-control" style = "height: 5%;" id = "class">
					<option value = ""> Select a class </option>
					<option value = "FY"> FY </option>
					<option value = "SY"> SY </option>
					<option value = "TY"> TY </option>
					<option value = "LY"> LY </option>
					<option value = "Passed Out"> Passed Out </option>
				</select>
				<br>
				<label for = "todate">TO: </label>
				<input type="date" class="form-control" name = "todate" id="todate" value = "">
				</div></td>
				
				<td style="text-align:center;"><div class="form-group">
				<label for = "dept"> DEPARTMENT: </label>
				<select name = "dept" class="form-control" style = "height: 5%;" id = "dept">
					<option value = ""> Select a department </option>
					<option value = "COMP"> COMP </option>
					<option value = "ETRX"> ETRX </option>
					<option value = "EXTC"> EXTC </option>
					<option value = "IT"> IT </option>
					<option value = "MECH"> MECH </option>
				</select>
				<br>
				<label for = "comm"> SELECT COMMITTEE: </label>
				<select class="form-control" name = "comm" id = "comm" style = "height: 5%;">
					<option value = ""> Select a committee </option>
					<?php
						$q = "SELECT name from commdet";
						$result = mysqli_query($conn, $q);
						
						if(mysqli_num_rows($result)>0) {
							while($row = mysqli_fetch_assoc($result)) {
								echo "<option value = '".$row['name']."'> ".$row['name']." </option>";
							}
						}
					?>
				</select>
				</div></td>
				
				<td style="text-align:center;"><div class="form-group">
				<label for = "designation"> DESIGNATION: </label>
				<select name = "designation" class="form-control" style = "height: 5%;" id = "dept">
					<option value = ""> Select employee designation </option>
					<option value = "Asst. Professor"> Asst. Professor </option>
					<option value = "Associate Professor"> Associate Professor </option>
					<option value = "HOD"> HOD </option>
					<option value = "Dean"> Dean </option>
					<option value = "Registrar"> Registrar </option>
				</select>
				<br>
				<label for = "gcat"> SELECT CATEGORY: </label>
				<select class="form-control" name = "gcat" id = "gcat" style = "height: 5%;">
					<option value = ""> Select a Category </option>
					<?php
						$q = "SELECT category from catdet";
						$result = mysqli_query($conn, $q);
						
						if(mysqli_num_rows($result)>0) {
							while($row = mysqli_fetch_assoc($result)) {
								echo "<option value = '".$row['category']."'> ".$row['category']." </option>";
							}
						}
					?>
				</select>
				</div></td>
			</tr>
			
			<tr>
				<td style="text-align:center;" colspan = "4"><div class="form-group">
				<button type="submit" class="btn btn-success form-control" name = "filsub" id = "filsub"> Apply Filter </button>
				</div></td>
			</tr>
		</form>
	</tbody>
</table>

<br>
<br>

<?php 
if(isset($_POST['rollsub'])) { 
	$roll = $_POST['rollfilter'];
	$q = "SELECT email FROM userdet WHERE id = '$roll'";
	$row = mysqli_fetch_assoc(mysqli_query($conn,$q));
	$_SESSION['rollfilter'] = $row['email'];
	header('location: princi.php');
}

if(isset($_POST['filsub'])) {
	$q = "";
	$class = "";
	$dept = "";
	$desig = "";
	
	if(!empty($_POST['role'])) {
		if($_POST['role'] == 'Student') {
			$q .= " AND (urole = 'Student')";
			$emq = "SELECT email FROM userdet";
			if(!empty($_POST['class']))
				$class = $_POST['class'];
			if(!empty($_POST['dept']))
				$dept = $_POST['dept'];
			
			if($class != "")
					$emq .= " WHERE class = '$class'";
			
			if($dept != "") {
				if($emq == "SELECT email FROM userdet")
					$emq .= " WHERE dept = '$dept'";
				else
					$emq .= " AND dept = '$dept'";
			}
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$q .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$q .= " OR uemail = '$em'";
					}
					$q .= ")";
				}
			}
		}
		
		elseif($_POST['role'] == 'Employee') {
			$q .= " AND (urole = 'Employee')";
			$emq = "SELECT email FROM facdet";
			
			if(!empty($_POST['dept']))
				$dept = $_POST['dept'];
			if(!empty($_POST['designation']))
				$desig = $_POST['designation'];
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
				
			if($desig != "") {
				if($emq == "SELECT email FROM facdet")
					$emq .= " WHERE designation = '$desig'";
				else
					$emq .= " AND designation = '$desig'";
			}
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$q .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$q .= " OR uemail = '$em'";
					}
					$q .= ")";
				}
			}
		}
		
		else {
			$q = "";
			
			$emq = "SELECT email FROM userdet";
			
			if($_POST['dept'] != "")
				$dept = $_POST['dept'];
			
			if($dept != "") 
				$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM userdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$q .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$q .= " OR uemail = '$em'";
					}
					$q .= ")";
				}
			}
			
			$emq = "SELECT email FROM facdet";
			
			if($dept != "")
					$emq .= " WHERE dept = '$dept'";
			
			if($emq != "SELECT email FROM facdet") {
				$studlist = mysqli_query($conn, $emq);
				if(mysqli_num_rows($studlist)>0) {
					$q .= " AND (0";
					while($row = mysqli_fetch_assoc($studlist)) {
						$em = $row['email'];
						$q .= " OR uemail = '$em'";
					}
					$q .= ")";
				}
			}
		}
	}

	if(!empty($_POST['fromdate'])){
		$from = $_POST['fromdate']." 00:00:00";
		$q .= " AND (timeofg >= '$from')";
	}
	
	if(!empty($_POST['todate'])) {
		$to = $_POST['todate']." 23:59:59";
		$q .= " AND (timeofg <= '$to')";
	}
	
	if(!empty($_POST['comm'])) {
		$name = $_POST['comm'];
		$catlist = mysqli_query($conn, "SELECT category from catdet WHERE comm_name = '$name'");
		if(mysqli_num_rows($catlist)>0) {
			$q .= " AND (0";
			while($row = mysqli_fetch_assoc($catlist)) {
				$cat = $row['category'];
				$q .= " OR gcat = '$cat'";
			}
			$q .= ")";
		}
	}
	
	else {
		if(!empty($_POST['gcat'])) {
			$cat = $_POST['gcat'];
			$q .= " AND (gcat = '$cat')";
		}
	}
	
	$_SESSION['filtercond'] = $q;
	$_SESSION['filterarray'] = $_POST;
	header('location: princi.php');
	
}
?>

</body>
</html>
<?php include 'footer.php';?>