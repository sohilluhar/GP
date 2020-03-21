<?php
ob_start();

session_start();
//require 'C:\xampp\php\pear\Net_SMTP-1.8.1\Net\SMTP.php';
include_once("includes/connection.php");

if (isset($_SESSION['previousp'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previousp']) {
        session_destroy();
    }
}

$flag1 = $_GET['flag1'];
$uid = $_SESSION['uid'];
$gid=$_SESSION['gid'];

if($flag1==1){
   // $_SESSION['id'] = $_POST['id'];
	$query="SELECT * FROM userdet WHERE id='".$uid."'  ";
	$qu="SELECT * from grievance where userid='".$uid."' ";
	$result=mysqli_query($conn,$query);
	$res=mysqli_query($conn,$qu);
	if($result && $res) {
		$row1=mysqli_fetch_assoc($result);
		$row2=mysqli_fetch_assoc($res);
		
	    $from = "committee.antir@gmail.com";
	    $to = $row1['email'];
		$subject = "Redressal of grievance subject: ".$row2['gsub'];
		$message = "Come to room Principal's office in A107 tomorrow at 12:30. Do not reply to this mail.";
		$headers = "From:" . $from;
		if (mail($to, $subject, $message, $headers)) {
			echo "<script type='text/javascript'>alert('Mail sent!');</script>";
			$query="UPDATE grievance SET status='Resolved' where userid='$uid' AND gid='$gid' ";
			$query1="UPDATE grievance SET timeofg=CURRENT_TIMESTAMP where userid='$uid' AND gid='$gid' ";
			$query2="DELETE FROM raised WHERE gid='$gid' ";
			$result=mysqli_query($conn,$query);
			if($result)
				header("location:princi.php");
		   	else 
		   		//echo "no";
		} 
		else {
		   echo "ERROR";
		}
	}
}

?>