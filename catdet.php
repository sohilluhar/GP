<?php
include_once("includes/connection.php");
include("header.php");
session_start();

if(isset($_POST['catedited'])) {
	$catname = $_SESSION['nameofeditedcat'];
	$caname = $_POST['catname'];
	$cinc = $_POST['cinc'];
	$isstudent = $_POST['isstudent'];
	$isemployee = $_POST['isemployee'];
	$cthreshrem = $_POST['cthreshrem'];
	$cthreshrais = $_POST['cthreshrais'];
	
	$q = "UPDATE catdet SET category = '$caname',comm_name = '$cinc',student = '$isstudent',employee = '$isemployee',thresh_rem = '$cthreshrem',thresh_raise = '$cthreshrais' where category = '$catname'" ;
	$qu = "UPDATE grievance SET gcat = '$caname' where gcat = '$catname' ";
	$l = mysqli_query($conn,$qu);
	$qu = "UPDATE resolved SET gcat = '$caname' where gcat = '$catname' ";
	$l = mysqli_query($conn,$qu);
	$qu = "UPDATE cancelled SET gcat = '$caname' where gcat = '$catname' ";
	$l = mysqli_query($conn,$qu);
	$r = mysqli_query($conn,$q);
	if($r) echo "Category details successfully updated";
	header("location: admin.php");
}

if(isset($_POST['catdel'])) {
	$catname = $_SESSION['nameofeditedcat'];
	$q = "DELETE from catdet where category = '$catname'" ;
	$r = mysqli_query($conn,$q);
	//if($r) echo "Committee details successfully updated";
	header("location: admin.php");
}

if(isset($_POST['catadded'])) {
	$catname = $_POST['catname'];
	$cinc = $_POST['cinc'];
	$isstudent = $_POST['isstudent'];
	$isemployee = $_POST['isemployee'];
	$cthreshrem = $_POST['cthreshrem'];
	$cthreshrais = $_POST['cthreshrais'];
	
	$q = "INSERT INTO catdet(category,comm_name,student,employee,thresh_rem,thresh_raise) VALUES ('$catname','$cinc','$isstudent','$isemployee','$cthreshrem','$cthreshrais')" ;
	$r = mysqli_query($conn,$q);
	if($r) echo "Category added successfully";
	header("location: admin.php");
}

if(isset($_POST['cats'])) {
	$_SESSION['nameofeditedcat'] = $_POST['cats'];
	$name = $_SESSION['nameofeditedcat'];

	$q = "SELECT * from catdet where category = '$name'";
	$re = mysqli_query($conn,$q);
	$row = mysqli_fetch_assoc($re);
?>

<!DOCTYPE HTML>
<html>
	<form method = "POST" action = "admin.php"><button type = "submit" name = "viewadmin" class = "btn btn-primary"><header align="left">Back to admin page</header></button></form>
	<body>
		<div class="container">
			<form method = "POST" id = "commedit" role = "form" action="" class="form-horizontal" enctype="multipart/form-data">
				<h2 align="center">Edit Category Details</h2>
				<div class="form-group">
					<label class="col-sm-3 control-label">Category Name: </label>
					<div class="col-sm-9">
						<?php echo '<input type = "text" name = "catname" id = "catname" required value = "'.$row['category'].'" class="form-control" autofocus>'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Committee Incharge:</label>
					<div class="col-sm-9">
					<?php echo '<input type = "text" name = "cinc" id = "cinc" required value = "'.$row['comm_name'].'" class="form-control">'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Is Committee Available For Students?</label>
					<div class="col-sm-9">
						<?php 
							if($row['student'] == 1) echo '<select name = "isstudent" style="height: 30px; width: 300px"><option value = 1 selected = "selected">Yes</option><option value = 0>No</option></select>';
							else echo '<select name = "isstudent" style="height: 30px; width: 300px"><option value = 1>Yes</option><option value = 0 selected = "selected">No</option></select>'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Is Committee Available For Employees? </label>
					<div class="col-sm-9">
						<?php
							if($row['employee'] == 1) echo '<select name = "isemployee" style="height: 30px; width: 300px"><option value = 1 selected = "selected">Yes</option><option value = 0>No</option></select>';
							else echo '<select name = "isemployee" style="height: 30px; width: 300px"><option value = 1>Yes</option><option value = 0 selected = "selected">No</option></select>'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Threshold Days For Reminder:</label>
					<div class="col-sm-9">
						<?php echo '<input type = "text" name = "cthreshrem" id = "cthreshrem" required value = "'.$row['thresh_rem'].'" class="form-control">'; ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Threshold Days For Raising:</label>
					<div class="col-sm-9">
						<?php echo '<input type = "text" name = "cthreshrais" id = "cthreshrais" required value = "'.$row['thresh_raise'].'" class="form-control">'; ?>
					</div>
				</div>

				<button type = "submit" name = "catedited" id = "catedited" class="btn btn-primary btn-block">Save Details</button>
				<button type = "submit" name = "catdel" id = "catdel" class="btn btn-primary btn-block">Delete Category</button>
			</form> <!-- /form -->
	    </div> <!-- ./container -->
	</body>
<?php

}
else if(isset($_POST['addcat'])) {
?>
<form method = "POST" action = "admin.php"><button type = "submit" name = "viewadmin" class = "btn btn-primary"><header align="left">Back to admin page</header></button></form>
<body>
	<div class="container">
		<form method = "POST" id = "catadd" role = "form" action="" class="form-horizontal" enctype="multipart/form-data">
			<h2 align="center">Add A New Category</h2>
			<div class="form-group">
				<label class="col-sm-3 control-label">Category Name: </label>
				<div class="col-sm-9">
					<input type = "text" name = "catname" id = "catname" required placeholder = "Enter Category Name" class="form-control" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Committee Incharge:</label>
				<div class="col-sm-9">
				<input type = "text" name = "cinc" id = "cinc" required placeholder = "Enter Committee Incharge Name" class="form-control" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Is Committee Available For Students? </label>
				<div class="col-sm-9">
					<select name = "isstudent" id = "isstudent" style="height: 30px; width: 300px" autofocus>
						<option value = ""> Select an option </option>
						<option value = "1">Yes</option>
						<option value = "0">No</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Is Committee Available For Employees? </label>
				<div class="col-sm-9">
					<select name = "isemployee" id = "isemployee" style="height: 30px; width: 300px" autofocus>
						<option value = ""> Select an option </option>
						<option value = "1">Yes</option>
						<option value = "0">No</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Threshold Days For Reminder:</label>
				<div class="col-sm-9">
					<input type = "text" name = "cthreshrem" id = "cthreshrem" required placeholder = "Enter reminder threshold days" class="form-control" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Threshold Days For Raising:</label>
				<div class="col-sm-9">
					<input type = "text" name = "cthreshrais" id = "cthreshrais" required placeholder = "Enter raising threshold days" class="form-control" autofocus>
				</div>
			</div>

			<button type = "submit" name = "catadded" id = "catadded" class="btn btn-primary btn-block">Save Details</button>
		</form> <!-- /form -->
    </div> <!-- ./container -->
</body>

<?php

}
?>