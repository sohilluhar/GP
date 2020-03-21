<?php
session_start();
ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

if(!isset($_SESSION['loggedInUser'])){
    header("location:index.php");
}
include_once("includes/connection.php");

$qu="SELECT * FROM grievance where gid='".$_POST['gid1']."'";
$res=mysqli_query($conn,$qu);
if(mysqli_num_rows($res)>0) {
	$row=mysqli_fetch_assoc($res);
}
$gid=$row['gid'];
$uid=$row['uemail'];
$gsub=$row['gsub'];
$gcat=$row['gcat'];
$gtype=$row['gtype'];
$gdes=$row['gdes'];
$gfile=$row['gfile'];
$timeofg=$row['timeofg'];
$raised = $row['raised'];
$actiontaken=$_POST['actiondet'];

if($_SESSION['loggedInUser'] == 'principal.engg@somaiya.edu')
	$actauth = 'Principal';
else {
	$q = "SELECT email, name FROM commdet WHERE email = '".$_SESSION['loggedInUser']."'";
	$actauthrow = mysqli_fetch_assoc(mysqli_query($conn, $q));
	$actauth = $actauthrow['name'];
}

$actiontaken = $row['act']."<p><b>".$actauth.": </b>".$actiontaken."</p>";
$to = $row['uemail'];
$namerow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT name FROM userdet WHERE email = '$to'"));
$name = $namerow['name'];

$subj = "Action Taken on Grievance ID: ".$gid;
$matter = "Dear ".$name.", <br/><br/>
Your grievance was taken into consideration by the authorities and appropriate action was taken on it. Following are the details of your grievance: <br/><br/>
<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Type</th><th>Description</th><th>Timestamp</th></tr>
<tr><td>".$row['gid']."</td><td>".$row['gsub']."</td><td>".$row['gcat']."</td><td>".$row['gtype']."</td><td>".$row['gdes']."</td><td>".$row['timeofg']."</td></tr></table><br/><br/>
<table border = '1'> <tr> The action taken is as follows: <br>
<p>".$actiontaken."</p> </tr></table>
<br/><br/>
You can visit the resolved grievances tab on your dashboard to find a PDF report of the grievance.<br/><br/>
<b>This is a system generated email, do not reply to this email!</b>";
//echo $matter;

if(isset($_POST['acttaken'])) {
	$que="INSERT INTO resolved values ('$gid', '$uid', '$gsub', '$gcat', '$gtype', '$gdes', '$gfile', '$timeofg' ,'Action Taken', '$raised', '$actiontaken', CURRENT_TIMESTAMP)";
	$re=mysqli_query($conn,$que);
	if($re) {
		
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
			//Server settings
			//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'committee.antir@gmail.com';                 // SMTP username
			$mail->Password = 'committee@antir1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom('committee.antir@gmail.com');
			$mail->addAddress($to);     // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
		    $mail->addCC($_SESSION['loggedInUser']);
			$mail->addCC('principal.engg@somaiya.edu');
		    //$mail->addBCC('brinda.ashar@somaiya.edu');

			//Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subj;
			$mail->Body    = $matter;
			$mail->AltBody = 'Your grievance was taken into consideration by the committee and appropriate action was taken on it. You can visit the resolved grievances tab on your dashboard to find a PDF report of the grievance. This is a system generated email, do not reply to this email!"';

			$mail->send();
			//echo 'Message has been sent';
		} 
		
		catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}

		$qu="DELETE from grievance where gid='$gid'";
				$res=mysqli_query($conn,$qu);
		
		if($_SESSION['loggedInUser']=='principal.engg@somaiya.edu') {
			header("location:princi.php");
		}
		else {
			$query="SELECT * FROM commdet WHERE email='".$_SESSION['loggedInUser']."'";
			$res=mysqli_query($conn,$query);
			if(mysqli_num_rows($res) > 0) 
				header("location:comm.php");
		}
	}

	else echo mysqli_error($conn);
}

else {
	$q = "UPDATE grievance SET act = '$actiondetails', status = 'Partially Solved' WHERE gid = '$gid'";
	$result = mysqli_query($conn, $q);
	
	if($_SESSION['loggedInUser']=='principal.engg@somaiya.edu') {
		header("location:princi.php");
	}
	else {
		$query="SELECT * FROM commdet WHERE email='".$_SESSION['loggedInUser']."'";
		$res=mysqli_query($conn,$query);
		if(mysqli_num_rows($res) > 0) 
			header("location:comm.php");
	}
}

?>