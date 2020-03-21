<?php include 'header.php';?>
<?php

session_start();
include_once("includes/connection.php");
if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}

$user=$_SESSION['loggedInUser'];
$uname=$_SESSION['name'];
/*$query = "SELECT name from userdet where email = '$user'";
$res=mysqli_query($conn,$query);

if(mysqli_num_rows($res)>0)
	$name = mysqli_fetch_assoc($res)['name'];
else
	$name = "Guest";*/
?>

<!Doctype html>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>HI, ".$uname."!</h4>" ?>
<form method = "POST" action = "empgriev.php"><button type = "submit" name = "viewgriev" class = "btn btn-primary"><header align="left">View your Grievances</header></button></form>
<head>
</head>
<body>
	<div class="container">
		<form method = "POST" id = "grievanceForm" role = "form" action="empgriev.php" class="form-horizontal" enctype="multipart/form-data">
			<h2 align="center">Grievance Form</h2>
			<input type = 'hidden' name = 'urole' value = 'Employee'>
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Subject: </label>
				<div class="col-sm-9">
					<input type = "text" name = "gsub" id = "gsub" required placeholder = "Enter Grievance Subject" class="form-control" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Category:</label>
				<div class="col-sm-9">
				<select name = "gcat" id = "gcat" required style="height: : 50px; width: 300px">
					<option value = ""> Select a category </option>
					<?php 
						$q = "SELECT category,comm_name,employee FROM catdet";
						$result = mysqli_query($conn, $q);
						if(mysqli_num_rows($result)>0) {
							while($row = mysqli_fetch_assoc($result)) {
								if($row['employee'] == 1)
									echo "<option value = '".$row['category']."'> ".$row['category']." - ".$row['comm_name']." </option>";
							}
						}
					?>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Description:</label>
				<div class="col-sm-9">
					<textarea name = "gdesc" id = "gdesc" class="form-control" required maxlength = "255" placeholder = "Describe the grievance"></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Attach any supporting files: </label>
				<div class="col-sm-9">
					<input type="file" name="atfile" id="atfile" class="form-control">
				</div>
			</div>
			<button type = "submit" name = "submit" id = "submit" class="btn btn-primary btn-block">Submit Grievance</button>
		</form> <!-- /form -->
    </div> <!-- ./container -->
</body>
</html>
<?php include 'footer.php';?>