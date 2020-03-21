<?php
include_once("includes/connection.php");
include("header.php");
session_start();

if(isset($_POST['comedited'])) {
	$email = $_SESSION['emailofeditedcom'];
	$name = $_POST['cname'];
	$nemail = $_POST['cemail'];
	$t = "SELECT name from commdet where email = '$email' ";
	$u = mysqli_fetch_assoc(mysqli_query($conn,$t));
	$q = "UPDATE commdet SET name = '$name',email = '$nemail' where email = '$email'" ;
	$qu = "UPDATE catdet SET comm_name = '$name' where comm_name = '".$u['name']."' ";
	$i = mysqli_query($conn,$qu);
	$r = mysqli_query($conn,$q);
	if($r) echo "Committee details successfully updated";
	header("location: admin.php");
}

if(isset($_POST['comdel'])) {
	$email = $_SESSION['emailofeditedcom'];
	$q = "DELETE from commdet where email = '$email'" ;
	$r = mysqli_query($conn,$q);
	//if($r) echo "Committee details successfully updated";
	header("location: admin.php");
}

if(isset($_POST['comadded'])) {
	$name = $_POST['cname'];
	$nemail = $_POST['cemail'];
	
	$q = "INSERT INTO commdet(name,email,category,thresh_days) VALUES ('$name','$nemail','$cat','$thresh')" ;
	$r = mysqli_query($conn,$q);
	if($r) echo "Committee added successfully";
	header("location: admin.php");
}

if(isset($_POST['comms'])) {
	$_SESSION['emailofeditedcom'] = $_POST['comms'];
	$email = $_SESSION['emailofeditedcom'];

	$q = "SELECT * from commdet where email = '$email'";
	$re = mysqli_query($conn,$q);
	$row = mysqli_fetch_assoc($re);
?>

<!DOCTYPE HTML>
<html>
	<form method = "POST" action = "admin.php"><button type = "submit" name = "viewadmin" class = "btn btn-primary"><header align="left">Back to admin page</header></button></form>
	<body>
		<div class="container">
			<form method = "POST" id = "commedit" role = "form" action="" class="form-horizontal" enctype="multipart/form-data">
				<h2 align="center">Edit Committee Details</h2>
				<div class="form-group">
					<label class="col-sm-3 control-label">Committee Name: </label>
					<div class="col-sm-9">
						<?php echo '<input type = "text" name = "cname" id = "cname" required value = "'.$row['name'].'" class="form-control" autofocus>'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Committee Email:</label>
					<div class="col-sm-9">
					<?php echo '<input type = "text" name = "cemail" id = "cemail" required value = "'.$row['email'].'" class="form-control" autofocus>'; ?>
					<?php $_SESSION['emailofeditedcom'] = $row['email']; ?>
					</div>
				</div>

				<button type = "submit" name = "comedited" id = "comedited" class="btn btn-primary btn-block">Save Details</button>
				<button type = "submit" name = "comdel" id = "comdel" class="btn btn-primary btn-block">Delete Committee</button>
			</form> <!-- /form -->
	    </div> <!-- ./container -->
	</body>
<?php

}
else if(isset($_POST['addcomm'])) {
?>
<form method = "POST" action = "admin.php"><button type = "submit" name = "viewadmin" class = "btn btn-primary"><header align="left">Back to admin page</header></button></form>
<body>
	<div class="container">
		<form method = "POST" id = "commadd" role = "form" action="" class="form-horizontal" enctype="multipart/form-data">
			<h2 align="center">Add A New Committee</h2>
			<div class="form-group">
				<label class="col-sm-3 control-label">Committee Name: </label>
				<div class="col-sm-9">
					<input type = "text" name = "cname" id = "cname" required placeholder = "Enter Committee Name" class="form-control" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Committee Email:</label>
				<div class="col-sm-9">
				<input type = "text" name = "cemail" id = "cemail" required placeholder = "Enter Committee Email" class="form-control" autofocus>
				</div>
			</div>
			<?php $_SESSION['emailofeditedcom'] = $row['email']; ?>
			<button type = "submit" name = "comadded" id = "comadded" class="btn btn-primary btn-block">Save Details</button>
		</form> <!-- /form -->
    </div> <!-- ./container -->
</body>

<?php

}



?>