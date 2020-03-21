<?php include 'header.php';?>

<?php
	session_start();
	include_once("includes/connection.php");
	if(!isset($_SESSION['loggedInUser'])){
		header("location:index.php");
	}

	$name=$_SESSION['name'];
?>

<?php
	if(isset($_POST['submit'])){
		date_default_timezone_set('Asia/Kolkata');
		$q=getdate();
		
		$day = $q['mday'];
		$month = $q['mon'];
		$year = $q['year'];
		
		if($day<10)
			$day = '0'.$day;
		if($month<10)
			$month = '0'.$month;
		
		$string = $day.$month.$year;
		$query = "SELECT gid FROM resolved WHERE gid REGEXP '".$string."'";
		
		$i = mysqli_num_rows(mysqli_query($conn,$query));
		
		if($i<10)
			$num = '000'.$i;
		elseif($i>9 && $i<100)
			$num = '00'.$i;
		elseif($i>99 && $i<1000)
			$num = '0'.$i;
		else
			$num = ''.$i;
		
		$gid = $string.$num;
		$uemail = $_POST['uemail'];
		$urole = $_POST['urole'];
		$gsub = $_POST['gsub'];	
		$gcat = $_POST['gcat'];
		$gtype = "";
		$gdes = $_POST['gdesc'];
		$file = NULL;
		$timeofg = $_POST['timeofg']." 00:00:00";
		$status = "Resolved Offline";
		$raised = 0;
		$actdet = $_POST['actdet'];
		
		if(isset($_FILES['atfile'])) {
			$fileName = $_FILES['atfile']['name'];
			$fileSize = $_FILES['atfile']['size'];
			$fileTmp = $_FILES['atfile']['tmp_name'];
			$fileType = $_FILES['atfile']['type'];
			$temp = explode('.',$fileName);
			$fileExt = strtolower(end($temp));
			$targetName = "proofDocs/".$gid.".".$fileExt;  
			  
				if(file_exists($targetName)) {   
					unlink($targetName);
				}      
				$moved = move_uploaded_file($fileTmp,$targetName);
				if($moved == true) {
					//successful
					$query1="INSERT INTO resolved VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gtype','$gdes','".$targetName."','$timeofg','$status','$raised','$actdet',CURRENT_TIMESTAMP)";
					$r=mysqli_query($conn,$query1); 
				}
				else{
					$query1="INSERT INTO resolved VALUES ('$gid','$uemail','$urole','$gsub','$gcat','$gtype','$gdes','$file','$timeofg','$status','$raised','$actdet',CURRENT_TIMESTAMP)";
					$r=mysqli_query($conn,$query1);	 
				}
				if($r) {
					$_SESSION['alert'] = 'Record added succesfully!';
					header('location: comm.php');
				}
				else
					echo mysqli_error($conn);
		}
		else {
			echo "No file detected";
		}
		
	}
?>

<!Doctype html>
<html>
<header align="right"><?php echo $_SESSION['loggedInUser'].' | <a href="logout.php">Logout</a>'; ?></header>
<?php echo "<h4>Hi, ".$_SESSION['name']."!</h4>" ?>
<form method = "POST" action = "comm.php"><button type = "submit" name = "back" class = "btn btn-primary"><header align="left">Back to Dashboard</header></button></form>

<head>
</head>

<body>
	<div class="container">
		<form method = "POST" action="" class="form-horizontal" enctype="multipart/form-data">
			<h2 align="center">Offline Report Form</h2>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Submitted By:</label>
				<div class="col-sm-9">
				<select name = "urole" id = "urole" class="form-control" style = "height: 5%;" required>
					<option value = ""> Select a role </option>
					<option value = "Student"> Student </option>
					<option value = "Employee"> Employee </option>
				</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Submitter's Email ID:</label>
				<div class="col-sm-9">
					<input type = "text" name = "uemail" id = "uemail" required placeholder = "Enter Email ID of the person that submitted the grievance" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Subject: </label>
				<div class="col-sm-9">
					<input type = "text" name = "gsub" id = "gsub" required placeholder = "Enter Grievance Subject" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Category: </label>
				<div class="col-sm-9">
					<select name = "gcat" id = "gcat" class="form-control" style = "height: 5%;" required>
					<option value = ""> Select Category </option>
					<?php
						$q = "SELECT category FROM catdet WHERE comm_name = '".$_SESSION['name']."'";
						$categories = mysqli_query($conn,$q);
						while($catrow = mysqli_fetch_assoc($categories)){
							$category = $catrow['category'];
							echo "<option value = '$category'> $category </option>";
						}
						echo "<input type = 'hidden' name = 'gcat' value = '$category'>";
					?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Description:</label>
				<div class="col-sm-9">
					<textarea name = "gdesc" id = "gdesc" class="form-control" required placeholder = "Describe the grievance"></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Grievance Submitted on: </label>
				<div class="col-sm-9">
					<input type="date" name="timeofg" id="timeofg" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Attach any supporting files: </label>
				<div class="col-sm-9">
					<input type="file" name="atfile" id="atfile" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Action Taken:</label>
				<div class="col-sm-9">
					<textarea name = "actdet" id = "actdet" class="form-control" required placeholder = "Mention the action taken on the grievance"></textarea>
				</div>
			</div>
			
			<button type = "submit" name = "submit" id = "submit" class="btn btn-primary btn-block">Add Record</button>
		</form> <!-- /form -->
    </div> <!-- ./container -->
</body>

</html>
<?php include 'footer.php';?>