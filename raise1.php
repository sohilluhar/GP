<?php
ob_start();

session_start();
//require 'C:\xampp\php\pear\Net_SMTP-1.8.1\Net\SMTP.php';
include_once("includes/connection.php");


$flag1 = $_GET['flag'];
$uid = $_SESSION['loggedInUser'];
$gid = $_SESSION['gid'];

if($flag1==1){
	//echo "entered if";
   // $_SESSION['id'] = $_POST['id'];
	$query="UPDATE grievance SET status='Raised to Principal', raised = '1' WHERE gid='".$gid."' ";
	
	$result=mysqli_query($conn,$query);
	//echo "before query";
	if($result) {
		//echo "query executed";
		echo "<script type='text/javascript'>alert('Issue raised to principal!');</script>";
		if($_SESSION['loggedInUser']=='committee.antir@somaiya.edu')
			header("location:antir.php");
		else if($_SESSION['loggedInUser']=='committee.hr@somaiya.edu')
			header("location:hr.php");
	}
	else echo mysqli_error($conn);
}

?>
