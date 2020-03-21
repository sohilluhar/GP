<?php
	
	session_start();

	if(!isset($_SESSION['loggedInUser'])){
		header("location:index.php");
	}
	include_once("includes/connection.php");
	
	$email = $_POST['uid'];
	$gid = $_POST['gid'];
	
	$qu = "INSERT INTO cancelled SELECT * FROM grievance WHERE uemail = '$email' AND gid = '$gid'";
	if(mysqli_query($conn,$qu)) {
		$qu = "UPDATE cancelled SET uptime = CURRENT_TIMESTAMP, status = 'Cancelled' WHERE gid = '$gid'";
		mysqli_query($conn,$qu);
		echo mysqli_error($conn);
		$qu = "DELETE FROM grievance WHERE gid = '$gid'";
		mysqli_query($conn,$qu);
		
		header('location: griev.php');
		//echo "Your grievance has been cancelled.";
	}
	else echo mysqli_error($conn);
?>