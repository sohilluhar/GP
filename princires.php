<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'includes/PHPMailer_5.2.0/vendor/autoload.php';

session_start();
ob_start();
include_once("includes/connection.php");

$uid = $_POST['uid1'];
$gid = $_POST['gid1'];

$actdet = $_POST['ad'];

$query="SELECT * FROM userdet WHERE email='".$uid."'  ";
$qu="SELECT * from grievance where gid='".$gid."' ";
$result=mysqli_query($conn,$query);
$res=mysqli_query($conn,$qu);
if(mysqli_num_rows($result)>0) {
	$row1=mysqli_fetch_assoc($result);
	$row2=mysqli_fetch_assoc($res);
    $gid1=$row2['gid'];
    $uemail=$row2['uemail'];
    $gsub=$row2['gsub'];
    $gcat=$row2['gcat'];
    $gtype=$row2['gtype'];
    $gdes=$row2['gdes'];
    $gfile=$row2['gfile'];
    $time=$row2['timeofg'];
    
	$to = $row1['email'];
	$name = $row1['name'];
	$subj = "Action Taken on Grievance ID: ".$gid;
	$matter = "Dear ".$name.", <br/><br/>
	Your grievance was taken into consideration by the principal and appropriate action was taken on it. Following are the details of your grievance: <br/><br/>
	<table border = '1'> <tr><th>Grievance ID</th><th>Subject</th><th>Category</th><th>Type</th><th>Description</th><th>Timestamp</th></tr>
	<tr><td>".$row2['gid']."</td><td>".$row2['gsub']."</td><td>".$row2['gcat']."</td><td>".$row2['gtype']."</td><td>".$row2['gdes']."</td><td>".$row2['timeofg']."</td></tr></table><br/><br/>
    <table border = '1'> <tr> The action taken is as follows: <br>
    <p>".$actdet."</p> </tr></table>
    <br/><br/>
	You can visit the resolved grievances tab on your dashboard to find a PDF report of the grievance.<br/><br/>
	<b>This is a system generated email, do not reply to this email!</b>";
	//echo $matter;
}

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
    //$mail->addCC('brinda.ashar@somaiya.edu');
    //$mail->addBCC('bcc@example.com');
    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subj;
    $mail->Body    = $matter;
    $mail->AltBody = 'Your grievance was taken into consideration by the principal and appropriate action was taken on it. You can visit the resolved grievances tab on your dashboard to find a PDF report of the grievance.<br/><br/>
		<b>This is a system generated email, do not reply to this email!</b>"';

    $mail->send();
    //echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

    $query="INSERT into resolved values('$gid1','$uemail','$gsub','$gcat','$gtype','$gdes','$gfile','$time','Resolved','$actdet') ";
    $result=mysqli_query($conn,$query);
    $query1="DELETE from grievance where gid='$gid' ";
    $re=mysqli_query($conn,$query1);
    if($result)
        header("location:princi.php");
    else 
        echo "ERROR: ".mysqli_error($conn);
?>