<?php
ob_start();

session_start();
//require 'C:\xampp\php\pear\Net_SMTP-1.8.1\Net\SMTP.php';
include_once("includes/connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

$uid = $_SESSION['loggedInUser'];
$gid = $_POST['gid1'];
$forto = $_POST['forwardto'];
$forwarddet = $_POST['forwarddet'];


	$query = "UPDATE grievance SET status='Forwarded to $forto', raised = '1', fordet = '$forwarddet' WHERE gid='".$gid."' ";
	$result = mysqli_query($conn,$query);
	$row1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM grievance WHERE gid = '$gid'"));
	$to = $row1['uemail'];
	$namerow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT fname FROM userdet WHERE email = '$to'"));
	$name = $namerow['fname'];
	
	if($forto == "Principal")
		$ccmail = "principal.engg@somaiya.edu";
	else {
		$ccmailrow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT email FROM commdet WHERE name = '$forto'"));
		$ccmail = $ccmailrow['email'];
	}

	if($result) {
		
		$subj = "Grievance ID: ".$row1['gid']." forwarded to $forto";
		$matter = "Dear ".$name.", <br/><br/>
		Your grievance has now been forwarded to appropriate authority for further assessment. Following are the details of your grievance: <br/><br/>
		<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Type</th><th>Description</th><th>Timestamp</th></tr>
		<tr><td>".$row1['gid']."</td><td>".$row1['gsub']."</td><td>".$row1['gcat']."</td><td>".$row1['gtype']."</td><td>".$row1['gdes']."</td><td>".$row1['timeofg']."</td></tr></table><br/><br/>
		<br/>
		The reason for forwarding the issue: <br/>".$row1['fordet']."<br/>
		The authority will look into the matter and revert back. You will be notified about the same via email in due course of time.<br/><br/>
		<b> This is a system generated email, do not reply to this email!</b>";
		//echo $matter;
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
			$mail->addCC($ccmail);
		    //$mail->addBCC('brinda.ashar@somaiya.edu');

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subj;
		    $mail->Body    = $matter;
		    $mail->AltBody = 'Your grievance has been acknowledged by the authorities, and necessary actions will be taken as convenient.';

		    $mail->send();
		    if($mail=='true') {
		    	echo "Reminder mail was sent";
		    }
		}
		catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}
	else echo mysqli_error($conn);

	$query="SELECT * FROM commdet WHERE email='".$_SESSION['loggedInUser']."'";
	$res=mysqli_query($conn,$query);
	if(mysqli_num_rows($res) > 0) 
		header("location:comm.php");
?>
