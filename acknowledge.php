<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

session_start();
ob_start();
include_once("includes/connection.php");

$uid = $_POST['uid'];
$gid=$_POST['gid'];

$query="SELECT email,fname FROM userdet WHERE email='".$uid."'  ";
$qu="SELECT * from grievance where gid='".$gid."' ";
echo $query;
echo $qu;
$result=mysqli_query($conn,$query);
$res=mysqli_query($conn,$qu);
if(mysqli_num_rows($result)>0) {
	$row1=mysqli_fetch_assoc($result);
	$row2=mysqli_fetch_assoc($res);

	$to = $row1['email'];
	$name = $row1['fname'];
	$subj = "Acknowledgement of Grievance ID: ".$gid;
	$matter = "Dear ".$name.", <br/><br/>
	Your grievance was recieved by our authorities and is currently under committee review. Please verify the details submitted. Following are the details of your grievance: <br/><br/>
	<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Type</th><th>Description</th><th>Timestamp</th></tr>
	<tr><td>".$row2['gid']."</td><td>".$row2['gsub']."</td><td>".$row2['gcat']."</td><td>".$row2['gtype']."</td><td>".$row2['gdes']."</td><td>".$row2['timeofg']."</td></tr></table>
	<br/><br/>
	The authorities will look into the matter and do the necessary. You will be notified about the same via email in due course of time.<br/><br/>
	<b>This is a system generated email, do not reply to this email!</b>";
	//echo $matter;
}

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
  //  $mail->isSMTP();                                      // Set mailer to use SMTP
   // $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    //$mail->SMTPAuth = true;                               // Enable SMTP authentication
    //$mail->Username = 'committee.antir@gmail.com';                 // SMTP username
   // $mail->Password = 'committee@antir1';                           // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    //$mail->setFrom('committee.antir@gmail.com');
    //$mail->addAddress($to);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    //$mail->isHTML(true);                                  // Set email format to HTML
   // $mail->Subject = $subj;
    //$mail->Body    = $matter;
    //$mail->AltBody = 'Your grievance has been acknowledged by the authorities, and necessary actions will be taken as convenient.';

    //$mail->send();
    //echo 'Message has been sent';
    $query="UPDATE grievance SET status='In progress' where uemail='$uid' AND gid='$gid' ";
	echo $query;
	$result=mysqli_query($conn,$query);
	if($result) {
		$query="SELECT * FROM commdet WHERE email='".$_SESSION['loggedInUser']."' ";
		$res=mysqli_query($conn,$query);
		if(mysqli_num_rows($res) > 0) {
			header('location: comm.php');
		}
	}
   	else 
   		echo "no";
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}


?>