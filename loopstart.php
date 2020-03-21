<?php
	session_start();
	include_once("includes/connection.php");

	$_SESSION['i']=0;
	while(true){
		$_SESSION['i']++;
		$sql = "UPDATE grievance SET act = '".$_SESSION['i']."' WHERE 1";
		mysqli_query($conn,$sql);
		sleep(2);
	}
?>